<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entity extends Model
{
    use HasFactory;
    protected $table = 'entities';

    protected $fillable = [
        'name',
        'label',
        'comments',
        'main_entity',
        'had_approval',
        'settings',
        'sort_order',
        'is_system',
        'is_active'
    ];

    protected $casts = [
        'settings' => 'array',
        'had_approval' => 'boolean',
        'is_system' => 'boolean',
        'is_active' => 'boolean'
    ];

    /**
     * 获取实体的所有字段
     */
    public function fields(): HasMany
    {
        return $this->hasMany(EntityField::class);
    }

    /**
     * 获取启用状态的字段
     */
    public function activeFields()
    {
        return $this->fields()->where('is_active', true);
    }

    /**
     * 检查实体是否需要审批
     */
    public function requiresApproval(): bool
    {
        return $this->had_approval === true;
    }
}
