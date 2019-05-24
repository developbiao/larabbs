<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'slug'];

    public function category()
    {
       return $this->belongsTo(Category::class);
    }

    public function user()
    {
       return $this->belongsTo(User::class);
    }

    public function scopeWithOrder($query, $order)
    {
        // different sort use different data logic
        switch($order)
        {
            case 'recent':
                $query = $this->recent();
                break;
            default:
                $query = $this->recentReplied();
        }
        return $query->with('user', 'category');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeRecentReplied($query)
    {
        // when topic trigger reply_count attribute
        // framework automatic update updated_at timestamp
       return $query->orderBy('updated_at', 'desc');
    }

    public function link($params = [])
    {
        return route('topics.show', array_merge( [$this->id, $this->slug], $params ) );
    }

}
