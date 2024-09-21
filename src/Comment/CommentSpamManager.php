<?php

namespace App\Comment;

use App\Entity\Comment;
use App\Service\RegExpSpamWordHelper;

class CommentSpamManager
{
    // Le problème est que la logique dépend du helper, donc
    // on interface mais en pensant l'interface depuis celui qui
    // l'utilise et on laisse le helper implémenter celle-ci.
    public function __construct(private CommentSpamCounterInterface $commentSpamCounter)
    {}

    public function validate(Comment $comment): void
    {
        $content = $comment->getContent();

        if ($this->commentSpamCounter->countSpamWords($content) >= 2) {
            // We could throw a custom exception if needed
            throw new \RuntimeException('Message detected as spam');
        }
    }
}

