<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Assert;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        TestResponse::macro('data', function ($key) {
            return $this->original->getData()[$key];
        });

        EloquentCollection::macro('assertEquals', function ($items) {
            Assert::assertCount($items->count(), $this);

            $this->zip($items)->each(function ($pair) {
                Assert::assertTrue($pair[0]->is($pair[1]));
            });

            // dd(collect([1, 2, 3])->zip([4, 5, 6])); //[[1, 4], [2, 5], [3, 6]];
        });
    }
}
