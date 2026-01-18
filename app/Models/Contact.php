<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'gender',
        'profile_image',
        'document',
        'status',
        'secondary_emails',
        'secondary_phones',
    ];

    protected $casts = [
        'secondary_emails' => 'array',
        'secondary_phones' => 'array',
    ];

    public function customFieldValues()
    {
        return $this->hasMany(ContactCustomFieldValue::class);
    }
}
