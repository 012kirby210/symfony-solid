<?php

namespace App\Service;

use App\Entity\BigFootSighting;
use App\Model\BigFootSightingScore;
use App\Scoring\PhotoFactor;
use App\Scoring\ScoreAdjusterInterface;
use App\Scoring\ScoringFactorInterface;

class SightingScorer
{

    /**
     * @var ScoringFactorInterface[] $scoringFactors
     * @var ScoreAdjusterInterface[] $scoringAdjusters
     * */
    public function __construct(private iterable $scoringFactors, private iterable $scoringAdjusters)
    {}
    public function score(BigFootSighting $sighting): BigFootSightingScore
    {
        $score = 0;

        foreach ($this->scoringFactors as $factor) {
            $score += $factor->score($sighting);
        }

        foreach ($this->scoringAdjusters as $adjuster) {
            $score += $adjuster->adjustScore($score, $sighting);
        }

        return new BigFootSightingScore($score);
    }
}
