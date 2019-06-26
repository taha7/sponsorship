<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Sponsorable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SponsorableTest extends TestCase
{

    /** @test */
    public function finding_a_sponsorable_by_slug()
    {
        $sponorable = factory(Sponsorable::class)->create(['slug' => 'full-stack-radio']);

        $foundSponsorable = Sponsorable::findOrFailBySlug('full-stack-radio');

        $this->assertTrue($foundSponsorable->is($sponorable));
    }

    /** @test */

    public function it_throw_an_exception_if_sponsorable_cannot_be_found_by_slug()
    {
        $this->expectException(ModelNotFoundException::class);

        Sponsorable::findOrFailBySlug('sulg-does-not-exist');
    }
}
