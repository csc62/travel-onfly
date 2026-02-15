<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTravelOrderRequest;
use App\Http\Resources\TravelOrderResource;
use App\Models\TravelOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TravelOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = TravelOrder::with('user'); // 👈 CARREGA O USER

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('destination')) {
            $query->where('destination', 'like', "%{$request->destination}%");
        }

        if ($request->has('from') && $request->has('to')) {
            $query->whereBetween('departure_date', [$request->from, $request->to]);
        }

        $orders = $query
            ->where('user_id', Auth::id())
            ->get();

        return TravelOrderResource::collection($orders);
    }

    public function store(StoreTravelOrderRequest $request): JsonResponse
    {
        $order = TravelOrder::create([
            'user_id' => Auth::id(),
            'destination' => $request->destination,
            'departure_date' => $request->departure_date,
            'return_date' => $request->return_date,
            'status' => 'solicitado'
        ]);

        // 👇 RECARREGA COM USER
        $order->load('user');

        return response()->json(new TravelOrderResource($order), 201);
    }

    public function show($id): JsonResponse
    {
        $order = TravelOrder::with('user') // 👈 CARREGA AQUI TAMBÉM
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json(new TravelOrderResource($order));
    }

    public function updateStatus($id): JsonResponse
    {
        $order = TravelOrder::with('user')->findOrFail($id);

        if (!Auth::user()->is_admin) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $requestStatus = request('status');

        if ($order->status === 'aprovado' && $requestStatus === 'cancelado') {
            return response()->json(['message' => 'Cannot cancel an approved order'], 400);
        }

        $order->status = $requestStatus;
        $order->save();

        return response()->json(new TravelOrderResource($order));
    }
}
