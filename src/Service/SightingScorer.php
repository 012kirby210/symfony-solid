<?php

namespace App\Service;

use App\Entity\BigFootSighting;
use App\Model\BigFootSightingScore;
use App\Scoring\PhotoFactor;
use App\Scoring\ScoringFactorInterface;

class SightingScorer
{

    /** @var ScoringFactorInterface[] $scoringFactors */
    public function __construct(private iterable $scoringFactors)
    {}
    public function score(BigFootSighting $sighting): BigFootSightingScore
    {
        $score = 0;

        foreach ($this->scoringFactors as $factor) {
            $score += $factor->score($sighting);
        }

        foreach ($this->scoringFactors as $factor) {
            $score += $factor->adjustScore($score, $sighting);
        }

        return new BigFootSightingScore($score);
    }
}
