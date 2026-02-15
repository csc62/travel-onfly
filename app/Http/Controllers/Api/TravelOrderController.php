<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTravelOrderRequest;
use App\Http\Requests\UpdateTravelOrderStatusRequest;
use App\Http\Resources\TravelOrderResource;
use App\Models\TravelOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TravelOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = TravelOrder::query();

        // Filtra por status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filtra por destino
        if ($request->has('destination')) {
            $query->where('destination', 'like', "%{$request->destination}%");
        }

        // Filtra por intervalo de datas
        if ($request->has('from') && $request->has('to')) {
            $query->whereBetween('departure_date', [$request->from, $request->to]);
        }

        // Só retorna pedidos do usuário autenticado
        $orders = $query->where('user_id', Auth::id())->get();

        return response()->json($orders);
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

        return response()->json(new TravelOrderResource($order), 201);
    }

    public function show($id): JsonResponse
    {
        $order = TravelOrder::where('id', $id)
            ->where('user_id', Auth::id()) // usuário só vê seus pedidos
            ->firstOrFail();

        return response()->json($order);
    }


    public function updateStatus($id): JsonResponse
    {
        $order = TravelOrder::findOrFail($id);

        // Apenas admin pode atualizar status
        if (!Auth::user()->is_admin) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $requestStatus = request('status'); // "aprovado" ou "cancelado"

        // Regra: não permitir cancelamento de pedido aprovado
        if ($order->status === 'aprovado' && $requestStatus === 'cancelado') {
            return response()->json(['message' => 'Cannot cancel an approved order'], 400);
        }

        $order->status = $requestStatus;
        $order->save();

        // Aqui você pode disparar notificação para o usuário
        // $order->user->notify(new OrderStatusUpdated($order));

        return response()->json($order);
    }

}
