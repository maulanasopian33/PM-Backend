<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workspacechat extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_chat';
    public $incrementing = false;
    protected $fillable = ['id_chat','workspace_id','message','from','type','reply','time'];
}
