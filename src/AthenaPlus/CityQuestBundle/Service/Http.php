<?php
/**
 * Created by PhpStorm.
 * User: pieter
 * Date: 10/02/16
 * Time: 11:10
 */

namespace AthenaPlus\CityQuestBundle\Service;


class Http
{

    protected $ch;

    function __construct()
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    }

    protected function getUpstream($url)
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        return curl_exec($this->ch);
    }

    public function getListOfNiceItems()
    {
        $response = $this->getUpstream('http://datahub.app/curated/cityquest');
        return json_decode($response);
    }

}