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
    
    public function __construct(AireRepository $aireRepository,TagRepository $tagRepository){
        $this->aireRepository = $aireRepository;
        $this->tagTepository = $tagRepository;
    }
    
    /**
     * Initialise database
     * @Rest\Post("/init")
     * @param Request $request
     * @return View
     */
    public function postInit(Request $request): View {
        $aires = $request->get('aires');
        foreach ($aires as $aire) {
            $aire = new Aire();
            $aire->setNom($aire['nom']);
            $aire->setUuid($aire['uuid']);
            $aire->setLat($aire['lat']);
            $aire->setLgt($aire['lgt']);
            $this->aireRepository->save($aire);
        }
        
        $tags = $request->get('tags');
        foreach ($tags as $tag){
            $tag = new Tag();
            $tag->setUuid($aire['uuid']);
            $tag->setNumserie($aire['numserie']);
            $tag->setData($tag['data']);
            //TODO associer une aire dans les données initiales
            $this->tagRepository->save($tag);
        }
    }
}

