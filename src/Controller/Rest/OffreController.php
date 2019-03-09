<?php

namespace App\Controller\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Aire;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use App\Entity\Offre;
use App\Repository\OffreRepository;
use App\Repository\AireRepository;
use FOS\RestBundle\Controller\FOSRestController;

class OffreController extends FOSRestController
{
    /**
     * 
     * @var OffreRepository
     */
    private $offreRepository;
    /**
     * 
     * @var AireRepository
     */
    private $aireRepository;
    
    public function __construct(AireRepository $aireRepository,OffreRepository $offreRepository){
        $this->aireRepository = $aireRepository;
        $this->offreRepository = $offreRepository;
    }
    
    /**
     * Creates a Offre resource
     * @Rest\Post("/Offres")
     * @param Request $request
     * @return View
     */
    public function postOffre(Request $request): View {
        $aire = $this->aireRepository->findBy(["uuid",$request->get('aireUuid')]);
        $offre = new Offre();
        $offre->setCodeEan($request->get('code_ean'));
        $offre->setUuid($request->get('uuid'));
        $offre->setDescription($request->get('description'));
        $offre->setCommerce($request->get('commerce'));
        $offre->setAire($aire);
        $this->offreRepository->save($offre);
        // In case our POST was a success we need to return a 201 HTTP CREATED response
        return View::create($offre, Response::HTTP_CREATED);
    }
    
    /**
     * Retrieves all Offre resource
     * @Rest\Get("/offres")
     */
    public function getAllOffre(): View{
        $offres = $this->offreRepository->findAll();
        $out = [];
        
        foreach ($offres as $offre) {
            $_o = [
                "uuid"=>$offre->getUuid(),
                "uuidAire"=>$offre->getAire()->getUuid(),
                "code_ean"=>$offre->getCodeEan(),
                "commerce"=>$offre->getCommerce(),
                "description"=>$offre->getDescription(),
                "nom"=>$offre->getNom(),
            ];
            
            $out[] = $_o;
        }
        
        
        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        return View::create($out, Response::HTTP_OK);
    }
    /**
     * Retrieves a Offre resource
     * @Rest\Get("/Offres/{OffreUuid}")
     */
    public function getOffre(string $offreUuid): View{
        $offre = $this->offreRepository->findOneBy(['uuid'=>$offreUuid]);
        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        return View::create($offre, Response::HTTP_OK);
    }
}
