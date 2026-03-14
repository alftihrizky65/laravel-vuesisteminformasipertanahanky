<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    // Use explicit table name because "feedback" is uncountable and Eloquent may use it as-is
    protected $table = 'feedbacks';

    protected $fillable = ['name', 'email', 'message', 'status', 'attempts', 'last_error'];
}