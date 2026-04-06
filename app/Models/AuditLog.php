<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($action, $entityType = null, $entityId = null, $description = null)
    {
        return static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }

    public static function recordCreated($entity, $description = null)
    {
        $entityType = class_basename($entity);
        return static::log('created', $entityType, $entity->id, $description ?? "Created {$entityType} #{$entity->id}");
    }

    public static function recordUpdated($entity, $description = null)
    {
        $entityType = class_basename($entity);
        return static::log('updated', $entityType, $entity->id, $description ?? "Updated {$entityType} #{$entity->id}");
    }

    public static function recordDeleted($entity, $description = null)
    {
        $entityType = class_basename($entity);
        return static::log('deleted', $entityType, $entity->id, $description ?? "Deleted {$entityType} #{$entity->id}");
    }

    public static function recordViewed($entityType, $entityId, $description = null)
    {
        return static::log('viewed', $entityType, $entityId, $description ?? "Viewed {$entityType} #{$entityId}");
    }

    public static function recordLogin($user, $description = null)
    {
        return static::log('login', 'User', $user->id, $description ?? "User logged in");
    }

    public static function recordLogout($user, $description = null)
    {
        return static::log('logout', 'User', $user->id, $description ?? "User logged out");
    }
}
