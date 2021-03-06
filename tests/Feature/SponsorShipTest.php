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

    /** @test */
    public function only_upcoming_sponsorables_are_listed()
    {
        $sponsorable = factory(Sponsorable::class)->create(['slug' => 'full-stack-radio']);

        $sponsorableSlots = new EloquentCollection([
            $slotA = factory(SponsorableSlot::class)->create(['publish_date' => Carbon::now()->subDays(10), 'sponsorable_id' => $sponsorable]),
            $slotB = factory(SponsorableSlot::class)->create(['publish_date' => Carbon::now()->subDays(1), 'sponsorable_id' => $sponsorable]),
            $slotC = factory(SponsorableSlot::class)->create(['publish_date' => Carbon::now()->addDays(1), 'sponsorable_id' => $sponsorable]),
            $slotD = factory(SponsorableSlot::class)->create(['publish_date' => Carbon::now()->addDays(10), 'sponsorable_id' => $sponsorable]),
        ]);

        $response = $this->get('full-stack-radio/sponsorships/new');
        $response->assertSuccessful();

        $this->assertCount(2, $response->data('sponsorableSlots'));
        $this->assertTrue($sponsorable->is($response->data('sponsorable')));

        $this->assertTrue($slotC->is($response->data('sponsorableSlots')[0]));
        $this->assertTrue($slotD->is($response->data('sponsorableSlots')[1]));
    }
}
