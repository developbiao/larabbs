<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{
    public function update(User $user, Reply $reply)
    {
        // return $reply->user_id == $user->id;
        return true;
    }

    public function destroy(User $user, Reply $reply)
    {
        // 只能是回复者或者是文章的作者可以删除回复
        return $user->isAuthorof($reply) || $user->isAuthorOf($reply->topic);
    }
}
