<?php

namespace App\Service;

use App\Entity\BigFootSighting;
use App\Model\BigFootSightingScore;

class SightingScorer
{

    /** @var [ScoringFactorInterface] $scoringFactors */
    public function __construct(private array $scoringFactors)
    {}
    public function score(BigFootSighting $sighting): BigFootSightingScore
    {
        $score = 0;

        foreach ($this->scoringFactors as $factor) {
            $score += $factor->score($sighting);
        }

        return new BigFootSightingScore($score);
    }
}
