<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;


class ContactRequest extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'contact_requests';
    protected $fillable = ['full_name', 'phone', 'email', 'message'];
}
