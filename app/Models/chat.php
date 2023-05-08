<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chat extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_chat';
    public $incrementing = false;
    protected $fillable = ['id_chat','task_id','message','from','type','reply','time'];
}
