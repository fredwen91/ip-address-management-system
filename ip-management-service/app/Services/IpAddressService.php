<?php

namespace App\Services;

use App\Models\IpAddress;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IpAddressService
{
    public function getAll($request): LengthAwarePaginator
    {
        $perPage = $request->integer('itemsPerPage', 10);

        return IpAddress::query()
            ->select('*')
            ->search($request->search)
            ->sort($request->sortKey, $request->sortOrder)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): IpAddress
    {
        return IpAddress::create([
            'ip_address'   => $data['ip_address'],
            'label' => $data['label'],
            'comment' => $data['comment'] ?? null,
            'user_id' => $data['user']['id'],
        ]);
    }

    public function update(string $id, array $data): IpAddress
    {
        $ipAddress = IpAddress::find($id);
        if (!$ipAddress) {
            throw new NotFoundHttpException('IP address not fount.');
        }

        $ipAddress->update([
            'ip_address'   => $data['ip_address'],
            'label' => $data['label'],
            'comment' => $data['comment'] ?? null,
        ]);

        return $ipAddress;
    }

    public function delete(string $id): void
    {
        $ipAddress = IpAddress::find($id);
        if (!$ipAddress) {
            throw new NotFoundHttpException('IP address not fount.');
        }

        $ipAddress->delete();
    }
}
