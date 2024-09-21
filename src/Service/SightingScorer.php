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
            if ($factor instanceof PhotoFactor && count($sighting->getImages()) === 0) {
                continue;
            }
            $score += $factor->score($sighting);
        }

        return new BigFootSightingScore($score);
    }
}
