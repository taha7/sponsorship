<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Sponsorable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use App\SponsorableSlot;
use Carbon\Carbon;

class SponsorShipTest extends TestCase
{
    /** @test */
    public function sponsorable_slots_are_listed_in_chronological_order()
    {
        $sponsorable = factory(Sponsorable::class)->create(['slug' => 'full-stack-radio']);

        $sponsorableSlots = new EloquentCollection([
            $slotA = factory(SponsorableSlot::class)->create(['publish_date' => Carbon::now()->addDays(10), 'sponsorable_id' => $sponsorable]),
            $slotB = factory(SponsorableSlot::class)->create(['publish_date' => Carbon::now()->addDays(30), 'sponsorable_id' => $sponsorable]),
            $slotC = factory(SponsorableSlot::class)->create(['publish_date' => Carbon::now()->addDays(5), 'sponsorable_id' => $sponsorable]),
        ]);

        $response = $this->get('full-stack-radio/sponsorships/new');

        $response->assertSuccessful();

        $this->assertCount(3, $response->data('sponsorableSlots'));

        $this->assertTrue($sponsorable->is($response->data('sponsorable')));

        $this->assertTrue($slotC->is($response->data('sponsorableSlots')[0]));
        $this->assertTrue($slotA->is($response->data('sponsorableSlots')[1]));
        $this->assertTrue($slotB->is($response->data('sponsorableSlots')[2]));

        // $sponsorableSlots->assertEquals($response->data('sponsorableSlots'));
    }
}
