<?php

namespace App\Controller;

use App\Entity\Athlet;
use App\Entity\Category;
use App\Entity\Company;
use App\Entity\Discipline;
use App\Entity\Participant;
use App\Entity\Team;
use App\Entity\TeamCreated;
use App\Entity\Type;
use App\Form\AthletType;
use App\Form\CategoryType;
use App\Form\CompanyType;
use App\Form\DisciplineType;
use App\Form\ParticipantType;
use App\Form\TeamCreatedType;
use App\Form\TeamType;
use App\Form\TypeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(Request $request, EntityManagerInterface $em)
    {
        /**
         * Add discipline Form
         */
        $discipline = new Discipline();
        $disciplineForm = $this->createForm(DisciplineType::class, $discipline);

        $disciplineForm->handleRequest($request);
        if($disciplineForm->isSubmitted() && $disciplineForm->isValid()){
            $em->persist($discipline);
            $em->flush();
            $this->addFlash('success', 'Discipline added');

            return $this->redirectToRoute('admin_home');
        }
        /**
         * Add Category Form
         */
        $category = new Category();
        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);
        if($categoryForm->isSubmitted() && $categoryForm->isValid()){
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Category added');

            return $this->redirectToRoute('admin_home');
        }

        /**
         * Add Type Form
         */
        $type = new Type();
        $typeForm = $this->createForm(TypeType::class, $type);

        $typeForm->handleRequest($request);
        if($typeForm->isSubmitted() && $typeForm->isValid()){
            $em->persist($type);
            $em->flush();
            $this->addFlash('success', 'Type added');

            return $this->redirectToRoute('admin_home');
        }
        /**
         * Add Company Form
         */
        $company = new Company();
        $companyForm = $this->createForm(CompanyType::class, $company);

        $companyForm->handleRequest($request);
        if($companyForm->isSubmitted() && $companyForm->isValid()){
            $em->persist($company);
            $em->flush();
            $this->addFlash('success', 'Company added');

            return $this->redirectToRoute('admin_home');
        }

        /**
         * Add Athlet Form
         */
        $athlet = new Athlet();
        $athletForm = $this->createForm(AthletType::class, $athlet);


        $athletForm->handleRequest($request);
        if($athletForm->isSubmitted() && $athletForm->isValid()){
            $em->persist($athlet);
            $em->flush();
            $this->addFlash('success', 'Athlet added');

            return $this->redirectToRoute('admin_home');
        }

        /**
         * Add Team Form
         */
        $team = new Team();
        $teamForm = $this->createForm(TeamType::class, $team);
        $participant = new Participant();
        $participantForm = $this->createForm(ParticipantType::class, $participant);

        $teamForm->handleRequest($request);
        $participantForm->handleRequest($request);
        if($teamForm->isSubmitted() && $teamForm->isValid() && $participantForm->isSubmitted()&& $participantForm->isValid()){
            $em->persist($team);
            $em->persist($participant);
            $em->flush();
            $this->addFlash('success', 'Team added');

            return $this->redirectToRoute('admin_home');
        }


        return $this->render('admin/home.html.twig', [
            'disciplineForm' => $disciplineForm->createView(),
            'categoryForm' => $categoryForm->createView(),
            'typeForm' => $typeForm->createView(),
            'companyForm' => $companyForm->createView(),
            'athletForm' => $athletForm->createView(),
            'teamForm' => $teamForm->createView(),
            'participantForm' => $participantForm->createView()
        ]);
    }
}
