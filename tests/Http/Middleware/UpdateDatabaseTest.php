<?php

namespace Tests\Http\Middleware;

use App\Services\DatabaseUpdater;
use Mockery;

class UpdateDatabaseTest extends \Tests\TestCase
{
    public function testItShouldSynchronizeDataBaseAtEachRequest()
    {
        \Route::get('foo', function () {
            return 'bar';
        });

        $this->disableExceptionHandling();

        $this->app->bind(DatabaseUpdater::class, function () {
            return Mockery::mock(DatabaseUpdater::class)->shouldReceive('synchronizeCats')->getMock();
        });

        $response = $this->get('foo');

        $this->assertEquals('bar', $response->baseResponse->content());
    }
}
