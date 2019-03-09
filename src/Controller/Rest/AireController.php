<?php

namespace App\Controller\Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use App\Entity\Aire;
use App\Repository\AireRepository;
use Symfony\Component\HttpFoundation\Response;

class AireController extends FOSRestController
{
    private $aireRepository;
    
    public function __construct(AireRepository $aireRepository){
        $this->aireRepository = $aireRepository;
    }
    
    /**
     * Creates an Aire resource
     * @Rest\Post("/aires")
     * @param Request $request
     * @return View
     */
    public function postAire(Request $request): View {
        $aire = new Aire();
        $aire->setNom($request->get('nom'));
        $aire->setUuid($request->get('uuid'));
        $aire->setLat($request->get('lat'));
        $aire->setLgt($request->get('lgt'));
        $this->aireRepository->save($aire);
        // In case our POST was a success we need to return a 201 HTTP CREATED response
        return View::create($aire, Response::HTTP_CREATED);
    }
    
    /**
     * Retrieves all Aire resource
     * @Rest\Get("/aires")
     */
    public function getAllTag(): View{
        $tags = $this->aireRepository->findAll();
        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        return View::create($tags, Response::HTTP_OK);
    }
    
    /**
     * Retrieves an Aire resource
     * @Rest\Get("/aires/{articleUuid}")
     */
    public function getAire(string $articleUuid): View{
        $article = $this->aireRepository->findOneBy(['uuid'=>$articleUuid]);
        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        return View::create($article, Response::HTTP_OK);
    }
    
}
