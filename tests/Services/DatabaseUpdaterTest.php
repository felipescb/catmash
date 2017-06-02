<?php

namespace Tests\Services;

use App\Models\Cat;
use App\Services\DatabaseUpdater;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Mockery;

class DatabaseUpdaterTest extends \Tests\TestCase
{

    /** @var  \Mockery\Mock|Filesystem */
    private $storage;
    private $apiDataStub;

    protected function setUp()
    {
        parent::setUp();

        $this->storage = Mockery::mock(Filesystem::class);
        config(['catmash.api_url' => $this->getStub()]);
        $apiDataStubRaw = file_get_contents($this->getStub());

        $this->apiDataStub = collect(json_decode($apiDataStubRaw, true)['images']);
    }

    private function getStub($stubName = '')
    {
        $file = __DIR__ . '/stubs/apiDataStub' . $stubName . '.json';

        if (!file_exists($file)) {
            throw new FileNotFoundException("This stubs does not exist : $file");
        };

        return $file;
    }

    public function testItShouldSynchronizeIfThereIsNoCachedData()
    {
        $this->storage->shouldReceive('exists')->andReturn(false);
        $this->storage->shouldReceive('put');

        $this->assertEquals(
            DatabaseUpdater::CREATED,
            $this->databaseUpdater()->synchronizeCats()
        );

        $this->assertDataAreSynced();
    }

    private function databaseUpdater()
    {
        return new DatabaseUpdater($this->storage, new Cat());
    }

    private function assertDataAreSynced(): void
    {
        $this->assertEquals(3, Cat::count());

        $this->assertEquals(
            $this->apiDataStub->toArray(),
            Cat::get(['url', 'id'])->toArray()
        );
    }

    public function testItShouldNotSynchronizeIfThereIsCachedDataAndDataAreEquals()
    {
        $this->storage->shouldReceive('exists')->andReturn(true);
        $this->storage->shouldReceive('get')->andReturn(md5_file($this->getStub()));

        $this->assertEquals(
            DatabaseUpdater::NOT_MODIFIED,
            $this->databaseUpdater()->synchronizeCats()
        );

        $this->assertEquals(0, Cat::count());
    }

    public function testItShouldRemoveACatIfItDoesNotExistAnymore()
    {
        $this->itShouldSyncWith('withOneLessCat', 2, 2);
    }

    private function itShouldSyncWith($stubName, $catsQuantity = 3, $rankQuantity = 3): void
    {
        $stubFile = $this->getStub(studly_case($stubName));

        config(['catmash.api_url' => $stubFile]);

        $this->apiDataStub->each(function ($cat) {
            Cat::create($cat + ['rating' => 134]);
        });
        $this->assertDataAreSynced();

        $this->storage->shouldReceive('exists')->andReturn(true);
        $this->storage->shouldReceive('get')->andReturn(md5_file($this->getStub()));
        $this->storage->shouldReceive('put')->with(DatabaseUpdater::API_CACHE_FILE, md5_file($stubFile));

        $this->assertEquals(
            DatabaseUpdater::UPDATED,
            $this->databaseUpdater()->synchronizeCats()
        );

        $this->assertEquals($catsQuantity, Cat::count());

        $this->assertEquals(
            json_decode(file_get_contents($stubFile), true)['images'],
            Cat::get(['url', 'id'])->toArray()
        );

        $this->assertEquals($rankQuantity, Cat::where(['rating' => 134])->count());
    }

    public function testItShouldAddACatIfItDidNotExist()
    {
        $this->itShouldSyncWith('withOneMoreCat', 4, 3);
    }

    public function testItShouldModifyUrlOfACatIfItChanges()
    {
        $this->itShouldSyncWith('withUrlChanged');
    }

    public function testItShouldModifyIdOfACatIfItChanges()
    {
        $this->itShouldSyncWith('withIdChanged');
    }

    public function testItShouldSyncAllChanges()
    {
        $this->itShouldSyncWith('withAllChanged', 3, 2);
    }
}
