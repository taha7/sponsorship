<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Sponsorable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use App\SponsorableSlot;

class SponsorShipTest extends TestCase
{
    /** @test */
    public function view_new_sponsorship_page()
    {
        $sponsorable = factory(Sponsorable::class)->create(['slug' => 'full-stack-radio']);

        $sponsorableSlots = new EloquentCollection([
            factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable]),
            factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable]),
            factory(SponsorableSlot::class)->create(['sponsorable_id' => $sponsorable]),
        ]);

        $response = $this->get('full-stack-radio/sponsorships/new');

        $response->assertSuccessful();


        $this->assertCount(3, $response->data('sponsorableSlots'));

        $this->assertTrue($sponsorable->is($response->data('sponsorable')));

        $sponsorableSlots->assertEquals($response->data('sponsorableSlots'));
    }
}
