<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classifications extends Model
{
    protected $table = 'classifications';
    protected $fillable = [
        'name',
        'openLevel',
        'isDisabled',
    ];
public function classificationData()
{
    return $this->hasMany(ClassificationData::class, 'dataId');
}

}
