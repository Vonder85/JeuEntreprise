<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Competition;
use App\Entity\Discipline;
use App\Entity\Event;
use App\Entity\Round;
use App\Entity\Type;
use App\Repository\CompetitionRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{

    /**
     * @Route("/", name="main")
     */
    public function main(Request $req, EntityManagerInterface $em)
    {
        $criteria = AdminController::buildCriteria($req, $em);
        $eventsFiltered = $em->getRepository(Event::class)->findEventsFiltered($criteria);
        $events = $em->getRepository(Event::class)->findAll();
        $competitions = $em->getRepository(Competition::class)->findAll();
        $disciplines = $em->getRepository(Discipline::class)->findAll();
        $categories = $em->getRepository(Category::class)->findAll();
        $types = $em->getRepository(Type::class)->findAll();
        $rounds = $em->getRepository(Round::class)->findAll();

        return $this->render('admin/events.html.twig', [
            "events" => $events,
            "criteria" => $criteria,
            "eventsFiltered" => $eventsFiltered,
            "competitions" => $competitions,
            "disciplines" => $disciplines,
            "categories" => $categories,
            "types" => $types,
            "rounds" => $rounds
        ]);
    }


    /**
     * @Route("/classementPays", name="classement_pays")
     * Retourne le classement des pays
     */
    public function classementDesPays(Request $request, CompetitionRepository $cr, EventRepository $er){
        $idCompetition = $request->query->get('competitionClassement');
        $competition = $cr->find($idCompetition);

        $pays = $er->recuperermedaillesPays($competition);

        return $this->render('competition/classementPays.html.twig', [
            'pays'=>$pays
        ]);
    }

}
