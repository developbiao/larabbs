<?php

namespace App\Observers;

use App\Handlers\SlugTranslateHandler;
use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function saving(Topic $topic)
    {
        // XSS filter
        $topic->body = clean($topic->body, 'user_topic_body');

        // generate topic excerpt
        $topic->excerpt = make_excerpt($topic->body);

        // use translate generate  title to slug
        if( !$topic->slug )
        {
           $topic->slug = app(SlugTranslateHandler::class)->translate( $topic->title );
        }

    }


}