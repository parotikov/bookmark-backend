<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $table = 'labels';
    protected $fillable = [
        'title'
    ];

    protected $visible = [
        'id',
        'title',
        'bookmarks_count',
        'private'
    ];

    public function bookmarks()
    {
        return $this->belongsToMany('App\Models\Bookmark');
    }

    public function scopeVisibility($query, $auth)
    {
        if(!$auth)
            $query->where('private', '=', 0);
    }

    public function getBookmarkCountAttribute()
    {
        return $this->bookmarks()->count();
    }

    public function scopeWithCount($query)
    {
        return $query;
    }
}
