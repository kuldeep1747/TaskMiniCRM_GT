<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMergeHistory extends Model
{
    protected $fillable = [
        'master_contact_id',
        'merged_contact_id',
    ];

}
