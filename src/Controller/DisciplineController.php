<?php

namespace App\Controller;

use App\Repository\DisciplineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DisciplineController extends AbstractController
{
    /**
     * @Route("/search/{idDiscipline}", name="search_discipline")
     * @Route("/search", name="search_discipline_ajax")
     */
    public function getUserAjax(Request $request, $idDiscipline,DisciplineRepository $dr)
    {
        if ($request->isXmlHttpRequest()) {
            $discipline = $dr->find($idDiscipline);

            $data =[
                'name' => $discipline->getName(),
                'multiMatchs' => $discipline->getMultiMatchs(),
            ];

            return new JsonResponse($data);
        }
        return new Response("Erreur : Ce n'est pas une requête Ajax",400);
    }
}
