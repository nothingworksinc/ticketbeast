<?php

use App\Ticket;
use App\Concert;
use App\Reservation;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReservationTest extends TestCase
{
    /** @test */
    function calculating_the_total_cost()
    {
        $tickets = collect([
            (object) ['price' => 1200],
            (object) ['price' => 1200],
            (object) ['price' => 1200],
        ]);

        $reservation = new Reservation($tickets);

        $this->assertEquals(3600, $reservation->totalCost());
    }

    /** @test */
    function reserved_tickets_are_released_when_a_reservation_is_cancelled()
    {
        $ticket1 = Mockery::mock(Ticket::class);
        $ticket1->shouldReceive('release')->once();

        $ticket2 = Mockery::mock(Ticket::class);
        $ticket2->shouldReceive('release')->once();

        $ticket3 = Mockery::mock(Ticket::class);
        $ticket3->shouldReceive('release')->once();

        $tickets = collect([$ticket1, $ticket2, $ticket3]);

        $reservation = new Reservation($tickets);

        $reservation->cancel();

    }
}
