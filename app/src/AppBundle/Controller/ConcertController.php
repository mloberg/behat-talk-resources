<?php

namespace AppBundle\Controller;

use AppBundle\Api\Service\ConcertService;
use JMS\DiExtraBundle\Annotation\Inject;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ConcertController extends Controller
{
    /**
     * @Inject("app.api.concerts")
     * @var ConcertService
     */
    private $concertService;

    /**
     * @Route("/concerts", name="concerts")
     */
    public function indexAction()
    {
        $concerts = $this->concertService->getConcerts();

        return $this->render('concerts/index.html.twig', [
            'concerts' => $concerts,
        ]);
    }

    /**
     * @Route("/concerts/{id}", name="concert_detail")
     */
    public function viewAction($id)
    {
        $concert = $this->concertService->getConcert($id);

        return $this->render('concerts/view.html.twig', [
            'concert' => $concert,
        ]);
    }
}
