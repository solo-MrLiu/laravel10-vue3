<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EntityField extends Model
{
    use HasFactory;

    protected $table = 'entity_fields';
    protected $fillable = [
        'entity_id',
        'name',
        'label',
        'type',
        'is_required',
        'is_unique',
        'is_primary_key',
        'is_nullable',
        'default_value',
        'validation_rules',
        'options',
        'sort_order',
        'is_system',
        'is_active'
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_unique' => 'boolean',
        'is_primary_key' => 'boolean',
        'is_nullable' => 'boolean',
        'is_system' => 'boolean',
        'is_active' => 'boolean'
    ];

    /**
     * 获取字段所属的实体
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    /**
     * 获取字段的选项列表
     */
    public function getOptionsList()
    {
        return $this->options ?? [];
    }

    /**
     * 检查字段是否为必填项
     */
    public function isRequired(): bool
    {
        return $this->is_required === true;
    }

    /**
     * 获取字段的验证规则
     */
    public function getValidationRules()
    {
        return $this->validation_rules ?: '';
    }
}
