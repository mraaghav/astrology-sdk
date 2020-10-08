<?php

namespace Prokerala\Api\Astrology\Result\Horoscope;


use Prokerala\Api\Astrology\Result\Horoscope\SadeSati\SaturnTransit;

class SadeSati
{

    /**
     * @var bool
     */
    private $isInSadeSati;
    /**
     * @var string
     */
    private $transitPhase;
    /**
     * @var string
     */
    private $description;

    /**
     * SadeSati constructor.
     * @param bool $isInSadeSati
     * @param string $transitPhase
     * @param string $description
     */
    public function __construct($isInSadeSati, $transitPhase, $description)
    {
        $this->isInSadeSati = $isInSadeSati;
        $this->transitPhase = $transitPhase;
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function getIsInSadeSati()
    {
        return $this->isInSadeSati;
    }

    /**
     * @return string
     */
    public function getTransitPhase()
    {
        return $this->transitPhase;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


}
