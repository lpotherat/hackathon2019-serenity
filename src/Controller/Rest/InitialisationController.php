<?php
namespace App\Controller\Rest;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use App\Entity\Aire;
use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Repository\AireRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Offre;
use App\Repository\OffreRepository;

class InitialisationController extends FOSRestController
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
    /**
     *
     * @var OffreRepository
     */
    private $offreRepository;
    
    public function __construct(AireRepository $aireRepository,TagRepository $tagRepository,OffreRepository $offreRepository){
        $this->aireRepository = $aireRepository;
        $this->tagRepository = $tagRepository;
        $this->offreRepository = $offreRepository;
    }
    
    /**
     * Initialize database
     * @Rest\Post("/init")
     * @param Request $request
     * @return View
     */
    public function postInit(Request $request): View {
        $aires = $request->get('aires');
        
        foreach ($aires as $aire) {
            $newAire = new Aire();
            $newAire->setNom($aire['nom']);
            $newAire->setUuid($aire['uuid']);
            $newAire->setLat($aire['lat']);
            $newAire->setLgt($aire['lgt']);
            $this->aireRepository->save($newAire);
        }
        
        $tags = $request->get('tags');
        foreach ($tags as $tag){
            $newTag = new Tag();
            $newTag->setUuid($tag['uuid']);
            $newTag->setNumserie($tag['numserie']);
            $newTag->setData($tag['data']);
            
            $aire = $this->aireRepository->findOneBy(["uuid"=>$tag['aireUuid']]);
            $newTag->setAire($aire);
            $this->tagRepository->save($newTag);
        }
        
        $offres = $request->get('offres');
        foreach ($offres as $offre){
            $newOffre = new Offre();
            $newOffre->setUuid($offre['uuid']);
            $newOffre->setCodeEan($offre['code_ean']);
            $newOffre->setDescription($offre['description']);
            $newOffre->setCommerce($offre['commerce']);
            $newOffre->setNom($offre['nom']);
            
            $aire = $this->aireRepository->findOneBy(["uuid"=>$offre['aireUuid']]);
            $newOffre->setAire($aire);
            $this->offreRepository->save($newOffre);
        }
        
        // In case our POST was a success we need to return a 202 HTTP ACCEPTED response
        return View::create(true, Response::HTTP_ACCEPTED);
    }
}

