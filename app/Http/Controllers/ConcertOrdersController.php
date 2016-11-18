<?php

namespace App\Http\Controllers;

use App\Order;
use App\Concert;
use Illuminate\Http\Request;
use App\Billing\PaymentGateway;
use App\Billing\PaymentFailedException;
use App\Exceptions\NotEnoughTicketsException;

class ConcertOrdersController extends Controller
{
    private $paymentGateway;

    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store($concertId)
    {
        $concert = Concert::published()->findOrFail($concertId);

        $this->validate(request(), [
            'email' => ['required', 'email'],
            'ticket_quantity' => ['required', 'integer', 'min:1'],
            'payment_token' => ['required'],
        ]);

        try {

            // Find some tickets
            $tickets = $concert->findTickets(request('ticket_quantity'));

            // Charge the customer for the tickets
            $this->paymentGateway->charge(request('ticket_quantity') * $concert->ticket_price, request('payment_token'));

            // Create an order for those tickets
            $order = Order::forTickets($tickets, request('email'));

            return response()->json($order, 201);

        } catch (PaymentFailedException $e) {
            return response()->json([], 422);
        } catch (NotEnoughTicketsException $e) {
            return response()->json([], 422);
        }
    }
}
