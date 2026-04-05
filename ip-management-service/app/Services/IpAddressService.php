<?php

namespace App\Services;

use App\Models\IpAddress;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        $ipAddress = IpAddress::create([
            'ip_address'   => $data['ip_address'],
            'label' => $data['label'],
            'comment' => $data['comment'] ?? null,
            'user_id' => $data['user']['id'],
        ]);

        AuditLogService::log([
            'user_id' => $data['user']['id'],
            'action' => 'create',
            'entity_type' => 'ip',
            'entity_id' => $ipAddress->id,
            'changes' => $ipAddress,
            'session_id' => session()->getId()
        ]);

        return $ipAddress;
    }

    public function update(string $id, array $data): IpAddress
    {
        $ipAddress = IpAddress::find($id);
        if (!$ipAddress) {
            throw new NotFoundHttpException('IP address not fount.');
        }

        $user = $data['user'];
        if (
            $user['role'] !== 'super_admin' &&
            $ipAddress->user_id !== $user['id']
        ) {
            throw new HttpException(403, 'Forbidden');
        }

        $oldIpAddress = $ipAddress->toArray();

        $ipAddress->update([
            'ip_address'   => $data['ip_address'],
            'label' => $data['label'],
            'comment' => $data['comment'] ?? null,
        ]);

        AuditLogService::log([
            'user_id' => $user['id'],
            'action' => 'update',
            'entity_type' => 'ip',
            'entity_id' => $ipAddress->id,
            'changes' => [
                'before' => $oldIpAddress,
                'after' => $ipAddress
            ],
            'session_id' => session()->getId()
        ]);

        return $ipAddress;
    }

    public function delete(array $user, string $id): void
    {
        $ipAddress = IpAddress::find($id);
        if (!$ipAddress) {
            throw new NotFoundHttpException('IP address not fount.');
        }

        if ($user['role'] !== 'super_admin') {
            throw new HttpException(403, 'Forbidden');
        }

        AuditLogService::log([
            'user_id' => $user['id'],
            'action' => 'delete',
            'entity_type' => 'ip',
            'entity_id' => $ipAddress->id,
            'session_id' => session()->getId()
        ]);

        $ipAddress->delete();
    }
}
