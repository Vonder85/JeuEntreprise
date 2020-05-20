<?php

namespace App\Controller;


use App\models\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 *@Route("/event", name="event_")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/", name="add")
     */
    public function addEvent()
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }


}
