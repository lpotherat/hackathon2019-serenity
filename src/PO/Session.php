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
    
    /**
     * 
     * @param \DateTime $date
     * @param Point $startPoint
     */
    public function __construct(\DateTime $date,Point $startPoint){
        $this->startPoint = $startPoint;
        $this->startTime = $date;
        $this->wayPoints = [];
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

