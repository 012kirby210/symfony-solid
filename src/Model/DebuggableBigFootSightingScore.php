<?php

namespace App\Model;

use App\Model\BigFootSightingScore;

class DebuggableBigFootSightingScore extends BigFootSightingScore
{

    // le constructeur viole le principe de substitution
    // les arguments avancés sont falacieux concernant pourquoi sur
    // un constructeur, ce n'est pas le principe ne s'applique pas :
    // supposemment, ça ne peut pas faire crasher l'app puisqu'
    // un objet passé en paramètre d'une fonction qui s'en sert n'a
    // pas besoin d'être instancié, sauf que ça exclut l'utilisation
    // des api de réflection.
    private float $calculationTime;
    public function __construct(int $score, float $calculationTime)
    {
        parent::__construct($score);
        $this->calculationTime = $calculationTime;
    }

    public function getCalculationTime(): float
    {
        return $this->calculationTime;
    }

}