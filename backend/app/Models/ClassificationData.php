<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassificationData extends Model
{
    use HasFactory;

    protected $table = 'classification_data';
    protected $fillable = [
        'dataId',
        'name',
        'fullName',
        'code',
        'color',
        'isHide',
        'parent_id',
        'level'
    ];

    public function project()
    {
        return $this->belongsTo(Classifications::class, 'dataId');
    }

    public function children()
    {
        return $this->hasMany(ClassificationData::class, 'parent_id', 'id');
    }
    public function parent()
    {
        return $this->belongsTo(ClassificationData::class, 'parent_id');
    }
}
