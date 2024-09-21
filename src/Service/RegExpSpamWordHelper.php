<?php

namespace App\Service;

use App\Comment\CommentSpamCounterInterface;

class RegExpSpamWordHelper implements CommentSpamCounterInterface
{
    private function getMatchedSpamWord(string $content): array
    {
        $badWordsOnComment = [];

        $regex = implode('|', $this->spamWords());

        preg_match_all("/$regex/i", $content, $badWordsOnComment);

        return $badWordsOnComment[0];
    }

    private function spamWords(): array
    {
        return [
            'follow me',
            'twitter',
            'facebook',
            'earn money',
            'SymfonyCats',
        ];
    }

    public function countSpamWords(string $content): int
    {
        return count($this->getMatchedSpamWord($content));
    }
}