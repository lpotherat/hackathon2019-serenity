<?php
namespace App\PO;

class Session
{
    /**
     * 
     * @var \DateTime
     */    
    private $startTime;
    
    /**
     * 
     * @var Point
     */
    private $startPoint;
    
    /**
     * 
     * @var Point[]
     */
    private $wayPoints;
    
    public function __construct(\DateTime $date,Point $startPoint){
        $this->startPoint = $startPoint;
    }
    
    /**
     * 
     * @param \DateTime $date
     * @param Point $point
     */
    public function pushWayPoint(\DateTime $date,Point $point){
        $this->wayPoints[$date->getTimestamp()] = $point;
    }
}

