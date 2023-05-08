<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class task extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_task';
    public $incrementing = false;
    protected $fillable = ['id_task','priority','deskripsi','name','avatar','workspace','assigment','start_date','due_date','status','avatar'];
}
