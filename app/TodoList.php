<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//Laravel didn't like the class name list so I  changed it to TodoList.
class TodoList extends Model
{
    protected $table = 'lists';
    protected $fillable = ['items', 'crossed'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}