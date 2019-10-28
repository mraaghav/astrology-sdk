<?php
/**
 * (c) Ennexa <api@prokerala.com>
 *
 * This source file is subject to the MIT license.
 *
 * PHP version 5
 *
 * @category API_SDK
 * @package  Astrology
 * @author   Ennexa <api@prokerala.com>
 * @license  https://api.prokerala.com/license.txt MIT License
 * @version  GIT: 1.0
 * @link     https://github.com/prokerala/astrology
 * @access   public
 **/
namespace Prokerala\Api\Astrology\Service;

use \Prokerala\Api\Astrology\Location;
use \Prokerala\Api\Astrology\Profile;
use \Prokerala\Api\Astrology\AstroTrait;
use \Prokerala\Common\Api\Client;
use \Prokerala\Common\Api\Exception\InvalidArgumentException;
use \Prokerala\Common\Api\Exception\QuotaExceededException;
use \Prokerala\Common\Api\Exception\RateLimitExceededException;

/**
 * Defines the KundliMatch
 *
 **/
class KundliMatch
{
    use AstroTrait;

    protected $apiClient = null;
    protected $slug = "kundli-matching";
    protected $ayanamsa = 1;

    protected $nakshatra = null;
    protected $tithi = null;
    protected $karna = null;
    protected $yoga = null;
    protected $vasara = null;
    protected $result = null;
    protected $input = null;

    /**
     * Function returns KundliMatch details
     *
     * @param  object $client api client object
     * @return void
     **/
    public function __construct(Client $client)
    {
        $this->apiClient = $client;
        $this->result = new \stdClass;

    }

    /**
     * Function returns KundliMatch details
     *
     * @param  object $location location details
     * @param  object $datetime date and time
     * @return array
     **/
    public function process(Profile $bride_profile, Profile $groom_profile)
    {
        $classNameSpace = "\\Prokerala\\Api\\Astrology\\Result\\";

        $arParameter = [
            'bride_dob' => $bride_profile->getDateTime()->format("Y-m-d\TH:i:s\Z"),
            'bride_coordinates' => $bride_profile->getLocation()->getCoordinates(),
            'bridegroom_dob' => $groom_profile->getDateTime()->format("Y-m-d\TH:i:s\Z"),
            'bridegroom_coordinates' => $groom_profile->getLocation()->getCoordinates(),
            'ayanamsa' => $this->ayanamsa,
        ];
        $result = $this->apiClient->doGet($this->slug, $arParameter);
        
        $this->input = $result->request;
        foreach (['bride_details' => $result->response->bride_details, 'bridegroom_details' => $result->response->bridegroom_details] as $res_key => $res_value) {
            foreach ($res_value as $res_key1 => $res_value1) {
                $class = $this->getClassName($res_key1, true);
                if ($class) {
                    if ($res_key1 == "planet_positions") {
                        foreach ($res_value1 as $planet_positions) {
                            $planet = new $class($planet_positions);
                            $this->result->$res_key->$res_key1[$planet->getId()] = $planet;
                        }
                    } else {
                        $this->result->$res_key->$res_key1 = new $class($res_value1);
                    }
                } else {
                    $this->result->$res_key->$res_key1 = $res_value1;
                }
            }
        }
        $this->result->result = $result->response->result;
        return $this;
    }

    /**
     * Function returns formated details
     *
     * @param  object $client client class object
     * @return void
     **/
    public function formatOutput($response)
    {
        $classNameSpace = "\\Prokerala\\Api\\Astrology\\Result\\";

         foreach ($response as $res_key => $res_value) {
            if (count((array)$res_value) > 1) {
                $this->formatOutput(((array)$res_value));
            }
            if (isset($this->arClassNameMap[$res_key])) {

                if (is_array($res_value)) {
                    foreach ($res_value as $rkey => $rvalue) {
                        $class = $classNameSpace.$this->arClassNameMap[$res_key];
                        $this->result->$res_key[] = new $class($rvalue);
                    }
                } else {
                    $class = $classNameSpace.$this->arClassNameMap[$res_key];
                    $this->result->$res_key = new $class($res_value);
                }
                
            } else {
                $this->result->$res_key = $res_value;
            }
        }
        return $this->result;
    }

    /**
     * Function returns panchang details
     *
     * @param  object $client client class object
     * @return void
     **/
    public function setApiClient(Client $client)
    {
        $this->apiClient = $client;
    }
    /**
     * Function returns panchang details
     *
     * @param  object $client client class object
     * @return void
     **/
    public function setAyanamsa($ayanamsa)
    {
        $this->ayanamsa = $ayanamsa;
    }


    /**
     * Function returns vasara details
     *
     * @return object
     **/
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Function returns input details
     *
     * @return object
     **/
    public function getInput()
    {
        return $this->input;
    }
}