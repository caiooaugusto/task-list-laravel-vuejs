<?php

namespace TaskList;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Task extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'task';

    protected $fillable = ['title','description','deadline_date_string','status'];
}
