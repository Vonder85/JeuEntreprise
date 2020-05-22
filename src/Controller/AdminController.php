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
use App\Form\TeamType;
use App\Form\TypeType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/disciplines", name="disciplines")
     */
    public function getDisciplines(EntityManagerInterface $em, Request $req){

        if($req->isXmlHttpRequest()){
            $data = [];
            $disciplines = $em->getRepository(Discipline::class)->findAll();
            dump($disciplines);
            for($i=0; $i < sizeof($disciplines); $i++){
                $data[$i] = [
                    'id' => $disciplines[$i]->getId(),
                    'name' => $disciplines[$i]->getName()];
            }
            return new JsonResponse($data);
        }
          return new Response("Erreur lors de la requête", 400);
    }

    /**
     * @Route("/categories", name="categories")
     */
    public function getCategories(EntityManagerInterface $em, Request $req){

        if($req->isXmlHttpRequest()){
            $data = [];
            $categories = $em->getRepository(Category::class)->findAll();
            for($i=0; $i < sizeof($categories); $i++){
                $data[$i] = [
                    'id' => $categories[$i]->getId(),
                    'name' => $categories[$i]->getName()];
            }
            return new JsonResponse($data);
        }
        return new Response("Erreur lors de la requête", 400);
    }

    /**
     * @Route("/types", name="types")
     */
    public function getTypes(EntityManagerInterface $em, Request $req){

        if($req->isXmlHttpRequest()){
            $data = [];
            $types = $em->getRepository(Type::class)->findAll();
            for($i=0; $i < sizeof($types); $i++){
                $data[$i] = [
                    'id' => $types[$i]->getId(),
                    'name' => $types[$i]->getName()];
            }
            return new JsonResponse($data);
        }
        return new Response("Erreur lors de la requête", 400);
    }

    /**
     * @Route("/athlets", name="athlets")
     */
    public function getAthlets(EntityManagerInterface $em, Request $req){

        if($req->isXmlHttpRequest()){
            $data = [];
            $athlets = $em->getRepository(Athlet::class)->findAll();
            for($i=0; $i < sizeof($athlets); $i++){
                $data[$i] = [
                    'id' => $athlets[$i]->getId(),
                    'name' => $athlets[$i]->getName(),
                    'firstname' => $athlets[$i]->getFirstname(),
                    'dateBirth' => $athlets[$i]->getDateBirth(),
                    'company' => $athlets[$i]->getCompany()->getName(),
                    'country' => $athlets[$i]->getCompany()->getCountry(),
                    'reference' => $athlets[$i]->getReference()];
            }
            return new JsonResponse($data);
        }
        return new Response("Erreur lors de la requête", 400);
    }

    /**
     * @Route("/teams", name="teams")
     */
    public function getTeams(EntityManagerInterface $em, Request $req, TeamRepository $tr){

        if($req->isXmlHttpRequest()){
            $data = [];
            $teams = $tr->findAll();
            for($i=0; $i < sizeof($teams); $i++){
                $data[$i] = [
                    'id' => $teams[$i]->getId(),
                    'name' => $teams[$i]->getName()];
            }
            return new JsonResponse($data);
        }
        return new Response("Erreur lors de la requête", 400);
    }

    /**
     * @Route("/companies", name="companies")
     */
    public function getCompanies(EntityManagerInterface $em, Request $req){

        if($req->isXmlHttpRequest()){
            $data = [];
            $companies = $em->getRepository(Company::class)->findAll();
            for($i=0; $i < sizeof($companies); $i++){
                $data[$i] = [
                    'id' => $companies[$i]->getId(),
                    'name' => $companies[$i]->getName(),
                    'country' => $companies[$i]->getCountry()];
            }
            return new JsonResponse($data);
        }
        return new Response("Erreur lors de la requête", 400);
    }

    /**
     * @Route("/events", name="events")
     */
    public function getEvents(EntityManagerInterface $em, Request $req){

        if($req->isXmlHttpRequest()){
            $data = [];
            $events = $em->getRepository(Event::class)->findAll();
            for($i=0; $i < sizeof($events); $i++){
                $data[$i] = [
                    'id' => $events[$i]->getId(),
                    'type' => $events[$i]->getType()->getName(),
                    'category' => $events[$i]->getCategory()->getName(),
                    'gender' => $events[$i]->getGender()];
            }
            return new JsonResponse($data);
        }
        return new Response("Erreur lors de la requête", 400);
    }

    /**
     * @Route("/users", name="users")
     */
    public function getUsers(EntityManagerInterface $em, Request $req){

        if($req->isXmlHttpRequest()){
            $data = [];
            $users = $em->getRepository(User::class)->findAll();
            for($i=0; $i < sizeof($users); $i++){
                $data[$i] = [
                    'id' => $users[$i]->getId(),
                    'lastname' => $users[$i]->getLastname(),
                    'firstname' => $users[$i]->getFirstname(),
                    'email' => $users[$i]->getEmail()];
            }
            return new JsonResponse($data);
        }
        return new Response("Erreur lors de la requête", 400);
    }

    /**
     * @Route("/vider", name="vider")
     */
    public function vider(Request $req){
        if($req->isXmlHttpRequest()){
            $data = [];
            return new JsonResponse($data);
        }
        return new Response("Erreur lors de la requête", 400);
    }
}
