<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexRequest;
use App\Http\Requests\StoreIpAddressRequest;
use App\Http\Requests\UpdateIpAddressRequest;
use App\Services\IpAddressService;

class IpAddressController extends Controller
{
    public function __construct(private IpAddressService $ipAddressService) {}

    public function index(IndexRequest $request)
    {
        $ipAddresses = $this->ipAddressService->getAll($request);

        return response()->json($ipAddresses);
    }

    public function store(StoreIpAddressRequest $request)
    {
        $ipAddress = $this->ipAddressService->create($request->all());

        return response()->json([
            'message' => 'IP Address created successfully.',
            'data' => $ipAddress
        ], 201);
    }

    public function show(string $id)
    {
        //
    }

    public function update(UpdateIpAddressRequest $request, string $id)
    {
        $ipAddress = $this->ipAddressService->update($id, $request->validated());

        return response()->json([
            'message' => 'IP Address updated successfully.',
            'data' => $ipAddress
        ]);
    }

    public function destroy(string $id)
    {
        $this->ipAddressService->delete($id);

        return response()->json([
            'message' => 'IP Address deleted successfully.'
        ]);
    }
}
