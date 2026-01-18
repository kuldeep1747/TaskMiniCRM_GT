<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactCustomFieldValue extends Model
{
    protected $fillable = [
        'contact_id','custom_field_id','value','history'
    ];

    protected $casts = [
        'history' => 'array',
    ];

    public function customField()
    {
        return $this->belongsTo(CustomField::class);
    }
}
