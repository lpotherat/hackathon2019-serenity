<?php
namespace App\Controller\Rest;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use App\PO\Session;
use App\PO\Point;
use App\Repository\TagRepository;
use App\Repository\OffreRepository;
use FOS\RestBundle\Controller\Annotations as Rest;

class CoreController extends FOSRestController
{
    const DEV = true;
    
    const K_MIN_ACCEPTABLE_ROAD_TIME = "minAcceptableRoadTime";
    const K_MAX_ACCEPTABLE_ROAD_TIME = "maxAcceptableRoadTime";
    const K_MIN_ACCEPTABLE_DISTANCE = "minAcceptableRoadDistance";
    const K_MAX_ACCEPTABLE_DISTANCE = "maxAcceptableRoadDistance";
    
    private static $paramsDev = [
        self::K_MIN_ACCEPTABLE_ROAD_TIME=>30,
        self::K_MAX_ACCEPTABLE_ROAD_TIME=>45,
        self::K_MIN_ACCEPTABLE_DISTANCE=>160,
        self::K_MAX_ACCEPTABLE_DISTANCE=>300,
    ];
    private static $paramsProd = [
        self::K_MIN_ACCEPTABLE_ROAD_TIME=>5400,
        self::K_MAX_ACCEPTABLE_ROAD_TIME=>8100,
        self::K_MIN_ACCEPTABLE_DISTANCE=>160,
        self::K_MAX_ACCEPTABLE_DISTANCE=>300,
    ];
    
    private $session;
    private $tagRepository;
    private $offreRepository;
    
    /**
     * 
     * @param SessionInterface $session
     * @param TagRepository $tagRepository
     */
    public function __construct(SessionInterface $session,TagRepository $tagRepository,OffreRepository $offreRepository){
        $this->session = $session;
        $this->tagRepository = $tagRepository;
        $this->offreRepository = $offreRepository;
    }
    
    /**
     * 
     * @return array
     */
    private static function getParams(){
        return static::DEV?static::$paramDev:static::$paramProd;
    }
    
    /**
     * Starts a tracking session
     * @Rest\Post("/session/start")
     * @param Request $request
     * @return View
     */
    public function start(Request $request): View {
        if(!$this->session->isStarted()){
            $this->session->start();
        }
        
        $currentSession = $this->session->get("session",null);
        
        if ($currentSession != null) {
            return View::create(["result"=>false,"message"=>"Vous êtes déjà en route !"],Response::HTTP_FORBIDDEN);
        }
        
        $startPoint = new Point();
        $startPoint->setLat($request->get('lat'));
        $startPoint->setLgt($request->get('lgt'));
        
        $session = new Session(new \DateTime(), $startPoint);
        
        $this->session->set("session",$session);
        
        // In case our POST was a success we need to return a 200 HTTP OK response
        return View::create(["result"=>true,"message"=>"Bonne route !","debug"=>var_export($session,true)], Response::HTTP_OK);
    }
    
    /**
     * Reset all the tracking session
     * @Rest\Post("/session/reset")
     * @param Request $request
     * @return View
     */
    public function reset(){
        if($this->session->isStarted()){
            $this->session->clear();
        }
    }
    
    /**
     * Starts a tracking session
     * @Rest\Post("/session/follow")
     * @param Request $request
     * @return View
     */
    public function follow(Request $request):View {
        if(!$this->session->isStarted()){
            $this->session->start();
        }
        $session = $this->session->get("session");
        if ($session instanceof Session) {
            
            $point = new Point();
            $point->setLat($request->get('lat'));
            $point->setLgt($request->get('lgt'));
            
            $session->pushWayPoint(new \DateTime(), $point);
            
            // In case our POST was a success we need to return a 200 HTTP OK response
            return View::create(["result"=>true], Response::HTTP_ACCEPTED);
        }
        return View::create(["result"=>false,"Vous n'êtes pas en route ... "], Response::HTTP_FORBIDDEN);
    }
    
    /**
     * @Rest\Post("/check")
     * @param Request $request
     * @return View
     */
    public function check(Request $request):View {
        
        /**
         * 
         * @var Session $session
         */
        $session = $this->session->get("session");
        if ($session == null) {
            return View::create(["result"=>false,"Vous n'avez pas démarré !"], Response::HTTP_FORBIDDEN);
        }
        
        $tagUuid = $request->get('uuid');
        $tag = $this->tagRepository->findOneBy(['uuid'=>$tagUuid]);
        if (!empty($tag)) {
            
            $timeSinceStart = $session->getTimeSinceStart();
            
            //$dist = $session->getTotalDistance(false);
            $distWP = $session->getTotalDistance(true);
            
            $params = static::getParams();
            
            $result = true;
            $message = "";
            if ($timeSinceStart < $params[static::K_MIN_ACCEPTABLE_ROAD_TIME]) {
                $result = false;
                $message = "Votre trajet est un peu cours, vous méritez la pause ?";
            }
            
            if ($timeSinceStart > $params[static::K_MAX_ACCEPTABLE_ROAD_TIME]) {
                $result = false;
                $message = "Vous n'avez pas respecté le temps règlementaire de 2h entre chaque pause :( ";
            }
            
            if ($distWP < $params[static::K_MIN_ACCEPTABLE_DISTANCE] || $distWP > $params[static::K_MAX_ACCEPTABLE_DISTANCE]) {
                $result = false;
                $message = "La distance parcourue n'est pas cohérente avec le temps passé à rouler";
            }
            
            if(!$result){
                return View::create(["result"=>false,"message"=>$message], Response::HTTP_OK);
            }
            
            $offres = $this->offreRepository->findWithTag($tag);
            
            
            // In case our POST was a success we need to return a 202 HTTP ACCEPTED response
            return View::create($offres, Response::HTTP_ACCEPTED);
        } else {
            //return a 404 HTTP NOT_FOUND response if tag does not exists
            return View::create(["result"=>false,"message"=>"Ce tag n'existe pas ! Gros malin !"], Response::HTTP_NOT_FOUND);
        }
    }
    
    
}

