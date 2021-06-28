<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';
    protected $fillable = [
        'country',
        'city',
        'state',
        'address',
        'last_name',
        'email',
        'photo',
        'mobile',
        'contract',
        'salary',
        'active',
    ];
}
