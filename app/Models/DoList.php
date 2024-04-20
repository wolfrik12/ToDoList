<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoList extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'time','user_id','status','task_counter'];
    protected $table='do_list';
}
