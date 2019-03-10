<?php
namespace App\PO;

class Session
{
    /**
     * 
     * @var int
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
        $this->startTime = $date->getTimestamp();
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
    
    /**
     * Retourne le nombre de secondes depuis le départ
     * @return int
     */
    public function getTimeSinceStart():int{
        $keys = array_keys($this->wayPoints);
        sort($keys,SORT_DESC);
        $max = isset($keys[0])?$keys[0]:time();
        
        return $max - $this->startTime;
    }
    
    /**
     * 
     * @return float
     */
    public function getTotalDistance(bool $byWayPoints):float{
        if ($byWayPoints) {
            $totalDist = 0;
            $p1 = $this->startPoint;
            foreach ($this->wayPoints as $wp) {
                $p2 = $wp;
                $totalDist += abs($this->dist($p1, $p2));
                $p1 = $p2;
            }
            return $totalDist;
        } else {
            $lpos = $this->getLastPosition();
            return abs($this->dist($this->startPoint,$lpos));
        }
    }
    
    /**
     * 
     * @return \App\PO\Point
     */
    private function getLastPosition(){
        $keys = array_keys($this->wayPoints);
        sort($keys,SORT_DESC);
        return  $this->wayPoints[$keys[0]];
    }
    
    /**
     *
     * @param Point $p1
     * @param Point $p2
     * @return number
     */
    private function dist(Point $p1,Point $p2){
        
        $earthRadiusKm = 6371;
        
        $dLat = deg2rad($p2->getLat()-$p1->getLat());
        $dLon = deg2rad($p2->getLgt()-$p1->getLgt());
        
        $lat1 = deg2rad($p1->getLat());
        $lat2 = deg2rad($p2->getLat());
        
        $a = sin($dLat/2) * sin($dLat/2) +
        sin($dLon/2) * sin($dLon/2) * cos($lat1) * cos($lat2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earthRadiusKm * $c;
        
    }
}

