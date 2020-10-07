<?php
namespace Prokerala\Api\Astrology\Result;

/**
 * Defines Nakshatra
 */
class Nakshatra
{


    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var float
     */
    private $longitude;

    /**
     * Nakshatra constructor.
     * @param int $id
     * @param string $name
     * @param float $longitude
     */
    public function __construct($id, $name, $longitude)
    {

        $this->id = $id;
        $this->name = $name;
        $this->longitude = $longitude;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }


}
