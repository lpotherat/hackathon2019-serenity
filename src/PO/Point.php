<?php
namespace App\PO;

class Point
{
    private $lat;
    private $lgt;
    /**
     * @return mixed
     */
    public function getLat():float
    {
        return $this->lat;
    }

    /**
     * @return mixed
     */
    public function getLgt():float
    {
        return $this->lgt;
    }

    /**
     * @param mixed $lat
     */
    public function setLat(float $lat)
    {
        $this->lat = $lat;
    }

    /**
     * @param mixed $lgt
     */
    public function setLgt(float $lgt)
    {
        $this->lgt = $lgt;
    }

    
    
}

