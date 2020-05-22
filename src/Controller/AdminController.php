<?php

namespace App\Controller;

use App\Entity\Athlet;
use App\Entity\Category;
use App\Entity\Company;
use App\Entity\Discipline;
use App\Entity\Event;
use App\Entity\Team;
use App\Entity\Type;
use App\Entity\User;
use App\Form\AthletType;
use App\Form\CategoryType;
use App\Form\CompanyType;
use App\Form\DisciplineType;
use App\Form\ParticipantType;
use App\Form\TeamType;
use App\Form\TypeType;
use App\Repository\AthletRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

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

        $teamForm->handleRequest($request);
        if($teamForm->isSubmitted() && $teamForm->isValid()){
            $em->persist($team);
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
        ]);
    }


    /**
     * @Route("/categories", name="categories")
     * show all categories
     */
    public function getCategories(EntityManagerInterface $em){

        $categories = $em->getRepository(Category::class)->findAll();

        return $this->render('admin/categories.html.twig', [
            "categories" => $categories
        ]);
    }

    /**
     * @Route("/types", name="types")
     * show all Types
     */
    public function getTypes(EntityManagerInterface $em){

        $types = $em->getRepository(Type::class)->findAll();

        return $this->render('admin/types.html.twig', [
            "types" => $types
        ]);
    }

    /**
     * @Route("/athlets", name="athlets")
     * show all athlets
     */
    public function getAthlets(EntityManagerInterface $em){

        $athlets = $em->getRepository(Athlet::class)->findAll();

        return $this->render('admin/athlets.html.twig', [
            "athlets" => $athlets
        ]);
    }

    /**
     * @Route("/teams", name="teams")
     * show all teams
     */
    public function getTeams(EntityManagerInterface $em, TeamRepository $tr){

        $teams = $tr->findAll();

        return $this->render('admin/teams.html.twig', [
            "teams" => $teams
        ]);
    }

    /**
     * @Route("/companies", name="companies")
     * show all companies
     */
    public function getCompanies(EntityManagerInterface $em){

        $companies = $em->getRepository(Company::class)->findAll();

        return $this->render('admin/companies.html.twig', [
            "companies" => $companies
        ]);
    }

    /**
     * @Route("/events", name="events")
     * show all events
     */
    public function getEvents(EntityManagerInterface $em){

        $events = $em->getRepository(Event::class)->findAll();

        return $this->render('admin/events.html.twig', [
            "events" => $events
        ]);
    }

    /**
     * @Route("/users", name="users")
     * show all users
     */
    public function getUsers(EntityManagerInterface $em){

        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/users.html.twig', [
            "users" => $users
        ]);
    }

    /**
     * @Route("/disciplines", name="discipline")
     * show all disciplines
     */
    public function getDisciplines(EntityManagerInterface $em){

            $disciplines = $em->getRepository(Discipline::class)->findAll();

            return $this->render('admin/disciplines.html.twig', [
                "disciplines" => $disciplines
            ]);
    }

    /**
     * @Route("/team/edit/{id}", name="edit_team", requirements={"id": "\d+"})
     * Edit Team
     */
    public function editTeam($id, TeamRepository $tr, AthletRepository $ar, Request $req){
        $athlets = $ar->findAll();
        $team = $tr->find($id);


        return $this->render('admin/edit/team.html.twig', [
            "athlets" => $athlets,
            "team" => $team
        ]);
    }

}
