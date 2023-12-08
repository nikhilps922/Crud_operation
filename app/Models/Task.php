<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'task';
    protected $primaryKey = 'task_id';
    protected $fillable = ['title', 'description', 'date'];

    public function category(){
        return $this->hasone(Category::class, 'task_id', 'task_id');
    }
}
