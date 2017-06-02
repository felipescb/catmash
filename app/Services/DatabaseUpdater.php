<?php

namespace App\Services;

use App\Models\Cat;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class DatabaseUpdater
{
    const NOT_MODIFIED = 304;
    const UPDATED = 200;
    const CREATED = 201;

    const API_CACHE_FILE = 'dataFromApi';

    private $storage;
    private $apiResponse;
    private $cacheExists;
    private $thereIsNoChange;
    private $catsFromApi;

    public function __construct(Filesystem $storage)
    {
        $this->storage = $storage;
    }

    public function synchronizeCats()
    {
        $this->makeApiCall();

        if ($this->apiIsNotReachable() || ($this->cacheExists() && $this->thereIsNoChange())) {
            return self::NOT_MODIFIED;
        }

        $this->cacheNewResponse()
            ->syncUrlsFromApi()
            ->syncIdsFromApi()
            ->deleteOldEntriesInDatabase();

        return $this->cacheExists() ? self::UPDATED : self::CREATED;
    }

    private function makeApiCall(): self
    {
        $this->apiResponse = @file_get_contents(config('catmash.api_url'));

        return $this;
    }

    private function apiIsNotReachable()
    {
        return $this->apiResponse === false;
    }

    private function cacheExists(): bool
    {
        return $this->cacheExists = $this->cacheExists ?? $this->storage->exists(self::API_CACHE_FILE);
    }

    private function thereIsNoChange(): bool
    {
        return $this->thereIsNoChange = $this->thereIsNoChange ??
            $this->storage->get(self::API_CACHE_FILE) === md5($this->apiResponse);
    }

    private function deleteOldEntriesInDatabase(): self
    {
        if ($this->cacheExists()) {
            $keysWhichAreNotInApi = $this->catsFromDatabase()->diffKeys($this->catsFromApi())->keys();

            if ($keysWhichAreNotInApi->isNotEmpty()) {
                Cat::whereIn('id', $keysWhichAreNotInApi)->delete();
            }
        }

        return $this;
    }

    private function catsFromDatabase(): Collection
    {
        return Cat::get(['id', 'url'])->sortBy('id')->toBase()->pluck('url', 'id');
    }

    private function catsFromApi(): Collection
    {
        return $this->catsFromApi ?? collect(json_decode($this->apiResponse, true)['images'])
                ->sortBy('id')->pluck('url', 'id');
    }

    private function syncIdsFromApi(): self
    {
        if ($this->cacheExists()) {
            $this->catsFromApi()->diffKeys($this->catsFromDatabase())->each(function ($url, $id) {
                Cat::updateOrCreate(compact('url'), ['id' => $id]);
            });
        }

        return $this;
    }

    private function syncUrlsFromApi(): self
    {
        $this->catsFromApi()->diff($this->catsFromDatabase())->each(function ($url, $id) {
            Cat::updateOrCreate(compact('id'), ['url' => $url]);
        });

        return $this;
    }

    private function cacheNewResponse(): self
    {
        $this->storage->put(self::API_CACHE_FILE, md5($this->apiResponse));

        return $this;
    }
}
