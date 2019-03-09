<?php

namespace App\Controller\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Aire;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Repository\AireRepository;
use FOS\RestBundle\Controller\FOSRestController;

class TagController extends FOSRestController
{
    /**
     * 
     * @var TagRepository
     */
    private $tagRepository;
    /**
     * 
     * @var AireRepository
     */
    private $aireRepository;
    
    public function __construct(AireRepository $aireRepository,TagRepository $tagRepository){
        $this->aireRepository = $aireRepository;
        $this->tagRepository = $tagRepository;
    }
    
    /**
     * Creates a Tag resource
     * @Rest\Post("/tags")
     * @param Request $request
     * @return View
     */
    public function postTag(Request $request): View {
        $aire = $this->aireRepository->findBy(["uuid",$request->get('aireUuid')]);
        $tag = new Tag();
        $tag->setNumserie($request->get('numserie'));
        $tag->setUuid($request->get('uuid'));
        $tag->setData($request->get('data'));
        $tag->setAire($aire);
        $this->tagRepository->save($tag);
        // In case our POST was a success we need to return a 201 HTTP CREATED response
        return View::create($tag, Response::HTTP_CREATED);
    }
    
    /**
     * Retrieves all Tag resource
     * @Rest\Get("/tags")
     */
    public function getAllTag(): View{
        $tags = $this->tagRepository->findAll();
        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        return View::create($tags, Response::HTTP_OK);
    }
    /**
     * Retrieves a Tag resource
     * @Rest\Get("/tags/{tagUuid}")
     */
    public function getTag(string $tagUuid): View{
        $tag = $this->tagRepository->findOneBy(['uuid'=>$tagUuid]);
        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        return View::create($tag, Response::HTTP_OK);
    }
}
