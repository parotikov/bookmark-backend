<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Bookmark extends Model
{
    use HasFactory;

    protected $table = 'bookmarks';
    
    protected $fillable = [
        'title',
        'url',
        'google_timestamp',
        'google_id',
    ];

    protected $dates = ['created_at', 'updated_at', 'disabled_at', 'added_at'];

    protected $casts = [
        'is_valid' => 'boolean',
    ];

    protected $visible = [
        'id',
        'title',
        'url',
        'is_valid',
        'updated_at',
        'added_at',
        'labels',
        'description'
    ];

    public function added() {
        // return Carbon::createFromTimestamp(substr($this->google_timestamp, 0, 10))->toDateTimeString();
        return substr($this->google_timestamp, 0, 10);
    }

    public function labels()
    {
        return $this->belongsToMany('App\Models\Label');
    }

    public function scopeVisibility($query, $auth)
    {
        if(!$auth) {
            $query->whereDoesntHave('labels', function ($query) {
                $query->where('private', '=', 1);
            });
            $query->WhereDoesntHave('labels', function ($query) {
                $query->where('title', 'LIKE', "%XVIDEOS%");
            });
        }
        return $query;
    }

    public function scopeHaveLabel($query, $label)
    {
        return $query->whereHas('labels', function ($query) use ($label) {
            $query->where('title', 'LIKE', "$label");
        });
    }

    public function scopeSearchLabel($query, $label, $auth)
    {
        if(!$auth) {
            $query->whereDoesntHave('labels', function ($query) {
                $query->where('private', '=', 1);
            });
            $query->WhereDoesntHave('labels', function ($query) {
                $query->where('title', 'LIKE', "%XVIDEOS%");
            });
        }
        if(is_iterable($label)) { //если массив меток
            // dump($label);
            $first = $label[0];
            $second = $label[1];
            $query->WhereHas('labels', function ($query) use ($first, $auth) {
                $query->where('title', 'LIKE', "%$first%");
                // dump($first);
            });  
            $query->WhereHas('labels', function ($query) use ($second, $auth) {
                $query->where('title', 'LIKE', "%$second%");
                // dump($second);
            }); 
        }
        else { //если метка одна
            $query->orWhereHas('labels', function ($query) use ($label, $auth) {
                $query->where('title', 'LIKE', "%$label%");
            });        
        }
        
        return $query;
        
    }
}
