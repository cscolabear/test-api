<?php

namespace Src\Models;

use \Illuminate\Database\Eloquent\Model;

class Article extends Model {
    protected $table = 'articles';
    protected $fillable = ['title', 'description', 'image', 'content'];
}
