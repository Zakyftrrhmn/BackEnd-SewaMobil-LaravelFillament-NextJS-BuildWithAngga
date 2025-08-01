<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingTransactionRequest;
use App\Http\Resources\Api\BookingTransactionApiResource;
use App\Http\Resources\Api\ViewBookingDetailsApiResource;
use App\Models\BookingTransaction;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class BookingTransactionController extends Controller
{
    //
    public function store(StoreBookingTransactionRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $vehicle = Vehicle::find($validatedData['vehicle_id']);

            if (!$vehicle) {
                return response()->json(['message' => 'Vehicle not found'], 400);
            }

            if ($request->hasFile('proof')) {
                $filePath = $request->file('proof')->store('proofs', 'public');
                $validatedData['proof'] = $filePath;
            }

            $price = $vehicle->price;
            $tax = 0.11;
            $totalTax = $tax * $price;
            $insurance = 500000;
            $grandTotal = $price + $totalTax + $insurance;


            $validatedData['total_amount'] = $grandTotal;

            $validatedData['total_max_amount'] = $totalTax;
            $validatedData['price'] = $price;
            $validatedData['insurance'] = $insurance;

            $validatedData['is_paid'] = false;
            $validatedData['duration'] = $vehicle->duration;
            $validatedData['booking_trx_id'] = BookingTransaction::generateUniqueTrxId();

            $bookingTransaction = BookingTransaction::create($validatedData);

            if (!$bookingTransaction) {
                return response()->json(['message' => 'Booking Transaction not created'], 500);
            }

            $bookingTransaction->load('vehicle');
            return new BookingTransactionApiResource($bookingTransaction);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function booking_details(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'booking_trx_id' => 'required|string',
        ]);

        $booking = BookingTransaction::where('phone', $request->phone)
            ->where('booking_trx_id', $request->booking_trx_id)
            ->with([
                'vehicle',
                'alpinaStore',
                'vehicle.category' => function ($query) {
                    $query->withCount('vehicles');
                }
            ])
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return new ViewBookingDetailsApiResource($booking);
    }
}
