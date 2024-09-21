<?php

namespace App\Comment;

use App\Entity\Comment;
use App\Service\RegExpSpamWordHelper;

class CommentSpamManager
{
    // Le problème est que la logique dépend du helper.
    public function __construct(private RegExpSpamWordHelper $regExpSpamWordHelper)
    {}

    public function validate(Comment $comment): void
    {
        $content = $comment->getContent();

        $badWordsOnComment = $this->regExpSpamWordHelper->getMatchedSpamWord($content);

        if (count($badWordsOnComment) >= 2) {
            // We could throw a custom exception if needed
            throw new \RuntimeException('Message detected as spam');
        }
    }
}

