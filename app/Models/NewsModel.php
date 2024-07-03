<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class NewsModel extends Model
{
    use HasFactory;
    protected $table = 'news_models';
    protected $fillable = [
        'slug',
        'title',
        'content',
        'image',
        'user_id',
        'desc'
    ];
    protected $primaryKey = 'id';


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
