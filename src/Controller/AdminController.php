<?php

namespace App\Controller;

use App\Entity\Athlet;
use App\Entity\Category;
use App\Entity\Company;
use App\Entity\Competition;
use App\Entity\Discipline;
use App\Entity\Event;
use App\Entity\Match;
use App\Entity\Participant;
use App\Entity\Participation;
use App\Entity\Round;
use App\Entity\Team;
use App\Entity\TeamCreated;
use App\Entity\Type;
use App\Entity\User;
use App\Form\AthletsCsvType;
use App\Form\AthletType;
use App\Form\CategoryType;
use App\Form\CompanyType;
use App\Form\CompetitionType;
use App\Form\DisciplineType;
use App\Form\EventType;
use App\Form\ParticipationAthletType;
use App\Form\ParticipationType;
use App\Form\RoundType;
use App\Form\TeamCreatedType;
use App\Form\TeamType;
use App\Form\TypeType;
use App\Form\UserType;
use App\Repository\AthletRepository;
use App\Repository\CategoryRepository;
use App\Repository\CompanyRepository;
use App\Repository\CompetitionRepository;
use App\Repository\DisciplineRepository;
use App\Repository\EventRepository;
use App\Repository\MatchRepository;
use App\Repository\ParticipationRepository;
use App\Repository\RoundRepository;
use App\Repository\TeamCreatedRepository;
use App\Repository\TeamRepository;
use App\Repository\TypeRepository;
use App\Repository\UserRepository;
use App\Utils\EventUtils;
use App\Utils\RencontreUtils;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {

        /**
         * Add discipline Form
         */
        $discipline = new Discipline();
        $disciplineForm = $this->createForm(DisciplineType::class, $discipline);

        $disciplineForm->handleRequest($request);
        if ($disciplineForm->isSubmitted() && $disciplineForm->isValid()) {
            $em->persist($discipline);
            $em->flush();
            $this->addFlash('success', 'Discipline ajoutée');

            return $this->redirectToRoute('admin_home');
        }
        /**
         * Add Category Form
         */
        $category = new Category();
        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);
        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Catégorie ajoutée');

            return $this->redirectToRoute('admin_home');
        }

        /**
         * Add Type Form
         */
        $type = new Type();
        $typeForm = $this->createForm(TypeType::class, $type);

        $typeForm->handleRequest($request);
        if ($typeForm->isSubmitted() && $typeForm->isValid()) {
            $em->persist($type);
            $em->flush();
            $this->addFlash('success', 'Type ajouté');

            return $this->redirectToRoute('admin_home');
        }
        /**
         * Add Company Form
         */
        $company = new Company();
        $companyForm = $this->createForm(CompanyType::class, $company);

        $companyForm->handleRequest($request);
        if ($companyForm->isSubmitted() && $companyForm->isValid()) {
            $em->persist($company);
            $em->flush();
            $this->addFlash('success', 'Entreprise ajoutée');

            return $this->redirectToRoute('admin_home');
        }

        /**
         * Add Athlet Form
         */
        $athlet = new Athlet();
        $athletForm = $this->createForm(AthletType::class, $athlet);
        $csvForm = $this->createForm(AthletsCsvType::class);

        $csvForm->handleRequest($request);
        if ($csvForm->isSubmitted() && $csvForm->isValid()) {
            $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);

            $filecsv = $csvForm->get('csv')->getData();
            $data = $serializer->decode(file_get_contents($filecsv), 'csv');
            dump($data);
            for ($i = 0; $i < sizeof($data); $i++) {
                $athlet = new Athlet();
                $company = $em->getRepository(Company::class)->find($data[$i]['company_id']);
                $date = new \DateTime($data[$i]['dateBirth']);

                $athlet->setName($data[$i]["name"]);
                $athlet->setFirstname($data[$i]["firstname"]);
                $athlet->setDateBirth($date);
                $athlet->setCompany($company);
                $athlet->setReference($data[$i]['reference']);
                $athlet->setCountry($data[$i]["country"]);

                $participant = new Participant();
                $participant->setAthlet($athlet);
                $participant->setName($athlet->getName());

                $em->persist($participant);
                $em->persist($athlet);
                $em->flush();

            }
            $this->addFlash('success', sizeof($data) . ' athlètes ajoutés');
            return $this->redirectToRoute('admin_athlets');
        }

        $athletForm->handleRequest($request);
        if ($athletForm->isSubmitted() && $athletForm->isValid()) {
            $em->persist($athlet);

            $participant = new Participant();
            $participant->setAthlet($athlet);
            $participant->setName($athlet->getName());
            $em->persist($participant);
            $em->flush();
            $this->addFlash('success', 'Athlète ajouté');

            return $this->redirectToRoute('admin_home');
        }

        /**
         * Add User Form
         */
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);


        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            //Hasher le password
            $hashed = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);
            $role = $request->request->get('role');
            if ($role === 'admin') {
                $roles[] = 'ROLE_ADMIN';
            } else {
                $roles[] = 'ROLE_USER';
            }
            $discipline = $request->request->get('discipline');
            $competition = $request->request->get('competition');
            $user->setRoles($roles);
            $user->setDiscipline($em->getRepository(Discipline::class)->find($discipline));
            $user->setCompetition($em->getRepository(Competition::class)->find($competition));


            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Utilisateur ajouté');

            return $this->redirectToRoute('admin_home');
        }

        /**
         * Add Team Form
         */
        $team = new Team();
        $teamForm = $this->createForm(TeamType::class, $team);

        $teamForm->handleRequest($request);
        if ($teamForm->isSubmitted() && $teamForm->isValid()) {
            $em->persist($team);


            $participant = new Participant();
            $participant->setTeam($team);
            $participant->setName($team->getName());
            $em->persist($participant);
            $em->flush();
            $this->addFlash('success', 'Equipe ajoutée');
            return $this->redirectToRoute('admin_home');
        }


        /**
         * Add Round Form
         */
        $round = new Round();
        $roundForm = $this->createForm(RoundType::class, $round);


        $roundForm->handleRequest($request);
        if ($roundForm->isSubmitted() && $roundForm->isValid()) {
            $em->persist($round);
            $em->flush();
            $this->addFlash('success', 'Tour ajouté');

            return $this->redirectToRoute('admin_home');
        }

        /**
         * Add Competition Form
         */
        $competition = new Competition();
        $competitionForm = $this->createForm(CompetitionType::class, $competition);

        $competitionForm->handleRequest($request);
        if ($competitionForm->isSubmitted() && $competitionForm->isValid()) {
            $em->persist($competition);
            $em->flush();
            $this->addFlash('success', 'Compétition ajoutée');

            return $this->redirectToRoute('admin_home');
        }

        /**
         * Add Event Form
         */
        $event = new Event();
        $eventForm = $this->createForm(EventType::class, $event);

        $eventForm->handleRequest($request);
        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $name = $event->getDiscipline()->getName() . ' ' . $event->getName() . ' ' . $event->getGender() . ' ' . $event->getCategory()->getName() . ' ' . $event->getType()->getName();
            $event->setName($name);
            $event->setPhaseIn(1);
            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'Evènement ajouté');

            return $this->redirectToRoute('admin_home');
        }

        $disciplinesSelect = $em->getRepository(Discipline::class)->findAll();
        $competitionsSelect = $em->getRepository(Competition::class)->findAll();


        return $this->render('admin/home.html.twig', [
            'disciplineForm' => $disciplineForm->createView(),
            'categoryForm' => $categoryForm->createView(),
            'typeForm' => $typeForm->createView(),
            'companyForm' => $companyForm->createView(),
            'athletForm' => $athletForm->createView(),
            'teamForm' => $teamForm->createView(),
            'userForm' => $userForm->createView(),
            'competitionForm' => $competitionForm->createView(),
            'eventForm' => $eventForm->createView(),
            'roundForm' => $roundForm->createView(),
            'csvForm' => $csvForm->createView(),
            "disciplines" => $disciplinesSelect,
            "competitions" => $competitionsSelect
        ]);
    }


    /**
     * @Route("/categories", name="categories")
     * show all categories
     */
    public function getCategories(EntityManagerInterface $em)
    {

        $categories = $em->getRepository(Category::class)->findAll();

        return $this->render('admin/categories.html.twig', [
            "categories" => $categories
        ]);
    }

    /**
     * @Route("/rounds", name="rounds")
     * show all rounds
     */
    public function getRounds(EntityManagerInterface $em)
    {

        $rounds = $em->getRepository(Round::class)->findAll();

        return $this->render('admin/rounds.html.twig', [
            "rounds" => $rounds
        ]);
    }

    /**
     * @Route("/types", name="types")
     * show all Types
     */
    public function getTypes(EntityManagerInterface $em)
    {

        $types = $em->getRepository(Type::class)->findAll();

        return $this->render('admin/types.html.twig', [
            "types" => $types
        ]);
    }

    /**
     * @Route("/athlets", name="athlets")
     * show all athlets
     */
    public function getAthlets(EntityManagerInterface $em)
    {

        $athlets = $em->getRepository(Athlet::class)->findAll();

        return $this->render('admin/athlets.html.twig', [
            "athlets" => $athlets
        ]);
    }

    /**
     * @Route("/teams", name="teams")
     * show all teams
     */
    public function getTeams(EntityManagerInterface $em, TeamRepository $tr)
    {

        $teams = $tr->findAll();

        return $this->render('admin/teams.html.twig', [
            "teams" => $teams
        ]);
    }

    /**
     * @Route("/companies", name="companies")
     * show all companies
     */
    public function getCompanies(EntityManagerInterface $em)
    {

        $companies = $em->getRepository(Company::class)->findAll();

        return $this->render('admin/companies.html.twig', [
            "companies" => $companies
        ]);
    }

    /**
     * @Route("/events", name="events")
     * show all events
     */
    public function getEvents(EntityManagerInterface $em)
    {

        $events = $em->getRepository(Event::class)->findAll();

        return $this->render('admin/events.html.twig', [
            "events" => $events
        ]);
    }

    /**
     * @Route("/users", name="users")
     * show all users
     */
    public function getUsers(EntityManagerInterface $em)
    {

        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/users.html.twig', [
            "users" => $users
        ]);
    }

    /**
     * @Route("/disciplines", name="discipline")
     * show all disciplines
     */
    public function getDisciplines(EntityManagerInterface $em)
    {

        $disciplines = $em->getRepository(Discipline::class)->findAll();

        return $this->render('admin/disciplines.html.twig', [
            "disciplines" => $disciplines
        ]);
    }

    /**
     * @Route("/competition", name="competitions")
     * show all competitions
     */
    public function getCompetitions(EntityManagerInterface $em)
    {

        $competitions = $em->getRepository(Competition::class)->findAll();

        return $this->render('admin/competitions.html.twig', [
            "competitions" => $competitions
        ]);
    }


    /**
     * @Route("/team/edit/{id}", name="edit_team", requirements={"id": "\d+"})
     * Edit Team
     */
    public function editTeam($id, TeamRepository $tr, AthletRepository $ar, Request $req, EntityManagerInterface $em)
    {
        $team = $tr->find($id);
        $teamCreated = new TeamCreated();
        $teamCreated->setTeam($team);

        $teamCreatedForm = $this->createForm(TeamCreatedType::class, $teamCreated);

        $teamCreatedForm->handleRequest($req);
        if ($teamCreatedForm->isSubmitted() && $teamCreatedForm->isValid()) {

            $em->persist($teamCreated);
            $em->flush();
        }
        $athletsTeam = $em->getRepository(TeamCreated::class)->athletinTeam($id);


        return $this->redirectToRoute('admin_see_team', [
            "id" => $id
        ]);
    }

    /**
     * @Route("/team/{id}", name="see_team", requirements={"id": "\d+"})
     * Edit Team
     */
    public function seeTeam($id, TeamRepository $tr, Request $req, EntityManagerInterface $em)
    {
        $team = $tr->find($id);

        $teamCreatedForm = $this->createForm(TeamCreatedType::class);

        $athletsTeam = $em->getRepository(TeamCreated::class)->athletinTeam($id);


        return $this->render('admin/edit/team.html.twig', [
            "teamCreatedForm" => $teamCreatedForm->createView(),
            "team" => $team,
            "athletsTeam" => $athletsTeam
        ]);
    }

    /**
     * @Route("/team/delete/{idTeam}", name="delete_team", requirements={"idTeam": "\d+"})
     */
    public function deleteTeam($idTeam, TeamRepository $tr, EntityManagerInterface $em)
    {

        $team = $tr->find($idTeam);
        $em->remove($team);
        $em->flush();

        $this->addFlash("success", "Equipe supprimée");
        return $this->redirectToRoute('admin_teams');
    }

    /**
     * @Route("/team/edit/{idTeam}/{idAthlet}", name="delete_athlet_team", requirements={"idTeam": "\d+", "idAthlet": "\d+"})
     */
    public function deleteAthletinTeam($idAthlet, $idTeam, TeamCreatedRepository $tcr)
    {

        $tcr->deleteAthletinTeam($idAthlet, $idTeam);

        $this->addFlash('success', 'athlète retiré de l\'équipe');
        return $this->redirectToRoute('admin_edit_team', ["id" => $idTeam]);
    }

    /**
     * @Route("/disicpline/{id}", name="edit_discipline", requirements={"id": "\d+"})
     * Edit a discipline
     */
    public function editDiscipline($id, EntityManagerInterface $em, DisciplineRepository $dr, Request $request)
    {
        $discipline = $dr->find($id);

        $disciplineForm = $this->createForm(DisciplineType::class, $discipline);
        $disciplineForm->handleRequest($request);
        if ($disciplineForm->isSubmitted() && $disciplineForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Discipline modifiée');
            return $this->redirectToRoute('admin_discipline');
        }
        return $this->render('admin/edit/discipline.html.twig', [
            'disciplineForm' => $disciplineForm->createView(),
            'discipline' => $discipline
        ]);
    }

    /**
     * @Route("/discipline/delete/{id}", name="delete_discipline", requirements={"id": "\d+"})
     */
    public function deleteDiscipline($id, EntityManagerInterface $em, DisciplineRepository $dr)
    {
        $discipline = $dr->find($id);
        $em->remove($discipline);
        $em->flush();

        $this->addFlash("success", "Discipline supprimée");
        return $this->redirectToRoute('admin_discipline');
    }

    /**
     * @Route("/category/{id}", name="edit_category", requirements={"id": "\d+"})
     * Edit a category
     */
    public function editCategory($id, EntityManagerInterface $em, CategoryRepository $cr, Request $request)
    {
        $category = $cr->find($id);

        $categoryForm = $this->createForm(categoryType::class, $category);
        $categoryForm->handleRequest($request);
        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Catégorie modifiée');
            return $this->redirectToRoute('admin_categories');
        }
        return $this->render('admin/edit/category.html.twig', [
            'categoryForm' => $categoryForm->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/category/delete/{id}", name="delete_category", requirements={"id": "\d+"})
     */
    public function deleteCategory($id, EntityManagerInterface $em, CategoryRepository $cr)
    {
        $category = $cr->find($id);
        $em->remove($category);
        $em->flush();

        $this->addFlash("success", "Catégorie supprimée");
        return $this->redirectToRoute('admin_categories');
    }

    /**
     * @Route("/round/{id}", name="edit_round", requirements={"id": "\d+"})
     * Edit a round
     */
    public function editRound($id, EntityManagerInterface $em, RoundRepository $rr, Request $request)
    {
        $round = $rr->find($id);

        $roundForm = $this->createForm(roundType::class, $round);
        $roundForm->handleRequest($request);
        if ($roundForm->isSubmitted() && $roundForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Tour modifié');
            return $this->redirectToRoute('admin_rounds');
        }
        return $this->render('admin/edit/round.html.twig', [
            'roundForm' => $roundForm->createView(),
            'round' => $round
        ]);
    }

    /**
     * @Route("/round/delete/{id}", name="delete_round", requirements={"id": "\d+"})
     */
    public function deleteRound($id, EntityManagerInterface $em, RoundRepository $rr)
    {
        $round = $rr->find($id);
        $em->remove($round);
        $em->flush();

        $this->addFlash("success", "Tour supprimé");
        return $this->redirectToRoute('admin_rounds');
    }

    /**
     * @Route("/athlet/{id}", name="edit_athlet", requirements={"id": "\d+"})
     * Edit an athlet
     */
    public function editAthlet($id, EntityManagerInterface $em, AthletRepository $ar, Request $request)
    {
        $athlet = $ar->find($id);

        $athletForm = $this->createForm(AthletType::class, $athlet);
        $athletForm->handleRequest($request);
        if ($athletForm->isSubmitted() && $athletForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Athlète modifié');
            return $this->redirectToRoute('admin_athlets');
        }
        return $this->render('admin/edit/athlet.html.twig', [
            'athletForm' => $athletForm->createView(),
            'athlet' => $athlet
        ]);
    }

    /**
     * @Route("/athlet/delete/{id}", name="delete_athlet", requirements={"id": "\d+"})
     * delete an athlet
     */
    public function deleteAthlet($id, EntityManagerInterface $em, AthletRepository $ar)
    {
        $athlet = $ar->find($id);
        $em->remove($athlet);
        $em->flush();

        $this->addFlash("success", "Athlète supprimé");
        return $this->redirectToRoute('admin_athlets');
    }

    /**
     * @Route("/event/{id}", name="edit_event", requirements={"id": "\d+"})
     * Edit an event
     */
    public function editEvent($id, EntityManagerInterface $em, EventRepository $er, Request $request)
    {
        $event = $er->find($id);

        $eventForm = $this->createForm(EventType::class, $event);
        $eventForm->handleRequest($request);
        $participation = new Participation();

        $participationForm = $this->createForm(ParticipationType::class, $participation);
        $participationForm->handleRequest($request);
        if ($participationForm->isSubmitted() && $participationForm->isValid()) {
            $participation->setEvent($event);
            $em->persist($participation);
            $em->flush();
            $this->addFlash('success', 'Participant ajouté');
        }

        $participationAthletForm = $this->createForm(ParticipationAthletType::class, $participation);
        $participationAthletForm->handleRequest($request);
        if ($participationAthletForm->isSubmitted() && $participationAthletForm->isValid()) {
            $participation->setEvent($event);
            $em->persist($participation);
            $em->flush();
            $this->addFlash('success', 'Participant ajouté');
        }

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Evènement modifié');
            return $this->redirectToRoute('admin_events');
        }
        $participants = $em->getRepository(Participation::class)->findParticipationInAnEvent($id);

        return $this->render('admin/edit/event.html.twig', [
            'eventForm' => $eventForm->createView(),
            'event' => $event,
            "participants" => $participants,
            "participationForm" => $participationForm->createView(),
            "participationAthletForm" => $participationAthletForm->createView()
        ]);
    }

    /**
     * @Route("/event/edit/{idEvent}/{idParticipation}", name="delete_participation_event", requirements={"idEvent": "\d+", "idParticipation": "\d+"})
     */
    public function deleteParticipationEvent($idEvent, $idParticipation, ParticipationRepository $pr, EntityManagerInterface $em)
    {
        dump((integer)$idParticipation);
        $pr->deleteParticipationEvent($idEvent, (integer)$idParticipation);
        dump($pr->getParticipationAvecUnParticipant($idEvent, $idParticipation));
        //$em->remove();
        $em->flush();
        $this->addFlash('success', 'Participation supprimée');
        return $this->redirectToRoute('admin_events');
    }

    /**
     * @Route("/event/delete/{id}", name="delete_event", requirements={"id": "\d+"})
     * delete an event
     */
    public function deleteEvent($id, EntityManagerInterface $em, EventRepository $er)
    {
        $event = $er->find($id);
        $em->remove($event);
        $em->flush();

        $this->addFlash("success", "Evènement supprimé");
        return $this->redirectToRoute('admin_events');
    }

    /**
     * @Route("/type/{id}", name="edit_type", requirements={"id": "\d+"})
     * Edit a type
     */
    public function editType($id, EntityManagerInterface $em, TypeRepository $tr, Request $request)
    {
        $type = $tr->find($id);

        $typeForm = $this->createForm(TypeType::class, $type);
        $typeForm->handleRequest($request);
        if ($typeForm->isSubmitted() && $typeForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Type modifié');
            return $this->redirectToRoute('admin_types');
        }
        return $this->render('admin/edit/type.html.twig', [
            'typeForm' => $typeForm->createView(),
            'type' => $type
        ]);
    }

    /**
     * @Route("/type/delete/{id}", name="delete_type", requirements={"id": "\d+"})
     * delete a type
     */
    public function deletetype($id, EntityManagerInterface $em, TypeRepository $tr)
    {
        $type = $tr->find($id);
        $em->remove($type);
        $em->flush();

        $this->addFlash("success", "Type supprimé");
        return $this->redirectToRoute('admin_types');
    }

    /**
     * @Route("/company/{id}", name="edit_company", requirements={"id": "\d+"})
     * Edit a company
     */
    public function editCompany($id, EntityManagerInterface $em, CompanyRepository $cr, Request $request)
    {
        $company = $cr->find($id);

        $companyForm = $this->createForm(CompanyType::class, $company);
        $companyForm->handleRequest($request);
        if ($companyForm->isSubmitted() && $companyForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Entreprise modifiée');
            return $this->redirectToRoute('admin_companies');
        }
        return $this->render('admin/edit/company.html.twig', [
            'companyForm' => $companyForm->createView(),
            'company' => $company
        ]);
    }

    /**
     * @Route("/company/delete/{id}", name="delete_company", requirements={"id": "\d+"})
     * delete a company
     */
    public function deleteCompany($id, EntityManagerInterface $em, CompanyRepository $cr)
    {
        $company = $cr->find($id);
        $em->remove($company);
        $em->flush();

        $this->addFlash("success", "Entreprise supprimée");
        return $this->redirectToRoute('admin_companies');
    }

    /**
     * @Route("/user/{id}", name="edit_user", requirements={"id": "\d+"})
     * Edit an user
     */
    public function editUser($id, EntityManagerInterface $em, UserRepository $ur, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $ur->find($id);

        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            //Hasher le password
            $hashed = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);
            $role = $request->request->get('role');
            if ($role === 'admin') {
                $roles[] = 'ROLE_ADMIN';
            } else {
                $roles[] = 'ROLE_USER';
            }
            $discipline = $request->request->get('discipline');
            $competition = $request->request->get('competition');

            $user->setRoles($roles);
            $user->setDiscipline($em->getRepository(Discipline::class)->find($discipline));
            $user->setCompetition($em->getRepository(Competition::class)->find($competition));
            $em->flush();
            $this->addFlash('success', 'Utilisateur modifié');
            return $this->redirectToRoute('admin_users');
        }
        $disciplinesSelect = $em->getRepository(Discipline::class)->findAll();
        $competitionsSelect = $em->getRepository(Competition::class)->findAll();
        return $this->render('admin/edit/user.html.twig', [
            'userForm' => $userForm->createView(),
            'user' => $user,
            "disciplines" => $disciplinesSelect,
            "competitions" => $competitionsSelect
        ]);
    }

    /**
     * @Route("/user/delete/{id}", name="delete_user", requirements={"id": "\d+"})
     * delete an user
     */
    public function deleteUser($id, EntityManagerInterface $em, UserRepository $ur)
    {
        $user = $ur->find($id);
        $em->remove($user);
        $em->flush();

        $this->addFlash("success", "Utilisateur supprimé");
        return $this->redirectToRoute('admin_users');
    }

    /**
     * @Route("/competition/{id}", name="edit_competition", requirements={"id": "\d+"})
     * Edit a competition
     */
    public function editCompetition($id, EntityManagerInterface $em, CompetitionRepository $cr, Request $request)
    {
        $competition = $cr->find($id);

        $competitionForm = $this->createForm(CompetitionType::class, $competition);
        $competitionForm->handleRequest($request);
        if ($competitionForm->isSubmitted() && $competitionForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Compétition modifiée');
            return $this->redirectToRoute('admin_competitions');
        }
        return $this->render('admin/edit/competition.html.twig', [
            'competitionForm' => $competitionForm->createView(),
            'competition' => $competition
        ]);
    }

    /**
     * @Route("/competition/delete/{id}", name="delete_competition", requirements={"id": "\d+"})
     * delete a competition
     */
    public function deleteCompetition($id, EntityManagerInterface $em, CompetitionRepository $cr)
    {
        $competition = $cr->find($id);
        $em->remove($competition);
        $em->flush();

        $this->addFlash("success", "Compétition supprimée");
        return $this->redirectToRoute('admin_competitions');
    }

    /**
     * @Route("/createMeet/{idEvent}", name="create_meet", requirements={"id": "\d+"})
     */
    public function createMeet($idEvent, EntityManagerInterface $em, ParticipationRepository $pr)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $participations = $pr->findParticipationInAnEvent($idEvent);
        $nbrPoules = $pr->nbrPoules($idEvent);
        return $this->render('admin/create/meet.html.twig', [
            "event" => $event,
            "participations" => $participations,
            "nbrPoules" => $nbrPoules
        ]);
    }

    /**
     * @Route("/planning/{idEvent}", name="see_planning_meets", requirements={"idEvent": "\d+"})
     */
    public function seePlanningMeets($idEvent, ParticipationRepository $pr, Request $request, EntityManagerInterface $em)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $participations = $pr->findParticipationInAnEventSimple($idEvent);
        $nbrPoules = $pr->nbrPoules($idEvent);
        $matchs = $em->getRepository(Match::class)->findMatchesWithAnEvent($event);

        //Tri les matchs par ordre chronologique
        $matchsTries = usort($matchs, function ($a, $b) {
            $ad = $a->getHeure();
            $bd = $b->getHeure();
            if ($ad == $bd) {
                return 0;
            } else {
                return $ad < $bd ? -1 : 1;
            }
        });
        return $this->render('admin/planning.html.twig', [
            "participants" => $participations,
            "matchs" => $matchs,
            "nbrPoules" => $nbrPoules,
            "event" => $event
        ]);
    }


    /**
     * @param $tabIdsParticipations
     * @param $event
     * @param EntityManagerInterface $em
     * Function establish list of meets
     * @Route("CreerRencontres/{idEvent}", name="creer_rencontre", requirements={"idEvent": "\d+"})
     */
    public function creerRencontres($idEvent, EntityManagerInterface $em)
    {

        $tabIdsParticipations = $em->getRepository(Participation::class)->findParticipationInAnEventSimple($idEvent);
        $event = $em->getRepository(Event::class)->find($idEvent);
        $anciennesRencontres = $em->getRepository(Match::class)->findMatchesWithAnEvent($event);
        if (sizeof($anciennesRencontres) > 0) {
            foreach ($anciennesRencontres as $match) {
                $em->remove($match);
            }
        }
        $nbTerrains = $event->getNbrFields();

        $matchs = RencontreUtils::generateRencontres($tabIdsParticipations, $event);
        foreach ($matchs as $match) {
            $em->persist($match);
        }

        if ($nbTerrains > floor(sizeof($tabIdsParticipations) / 2)) {
            $nbTerrains = floor(sizeof($tabIdsParticipations) / 2);
        }

        RencontreUtils::affectationTerrains($matchs, $nbTerrains,$event);
        $em->flush();
        return $this->redirectToRoute('admin_see_planning_meets', [
            "idEvent" => $idEvent
        ]);
    }

    /**
     * fonction qui crée les rencontres des poules
     * @Route("/creerMatchsPoules/{idEvent}", name="creer_matchs_poules", requirements={"idEvent": "\d+"})
     */
    function creerRencontresPoules($idEvent, ParticipationRepository $pr, EntityManagerInterface $em)
    {
        $poules = $pr->nbrPoules($idEvent);
        $totalParticipants = $pr->findParticipationInAnEventSimple($idEvent);
        for ($i = 0; $i < sizeof($poules); $i++) {
            $participations[] = $pr->getParPoules($idEvent, $poules[$i]->getPoule());
        }

        $event = $em->getRepository(Event::class)->find($idEvent);
        $nbTerrains = $event->getNbrFields();

        $matchs = RencontreUtils::generateRencontresPoules($participations, $event);

        foreach ($matchs as $match) {
            $em->persist($match);
        }

        if ($nbTerrains > floor(sizeof($totalParticipants) / 2)) {
            $nbTerrains = floor(sizeof($totalParticipants) / 2);
        }
        shuffle($matchs);
        RencontreUtils::affectationTerrains($matchs, $nbTerrains, $event);
        $em->flush();
        return $this->redirectToRoute('admin_see_planning_meets', [
            "idEvent" => $idEvent
        ]);
    }

    /**
     * @param $tabIdsParticipations
     * @param $event
     * @param EntityManagerInterface $em
     * Function establish list of meets
     * @Route("CreerRencontresAllerRetour/{idEvent}", name="creer_rencontre_aller_retour", requirements={"idEvent": "\d+"})
     */
    public function creerRencontresAllerRetour($idEvent, EntityManagerInterface $em)
    {

        $tabIdsParticipations = $em->getRepository(Participation::class)->findParticipationInAnEventSimple($idEvent);
        $event = $em->getRepository(Event::class)->find($idEvent);
        $anciennesRencontres = $em->getRepository(Match::class)->findMatchesWithAnEvent($event);
        if (sizeof($anciennesRencontres) > 0) {
            foreach ($anciennesRencontres as $match) {
                $em->remove($match);
            }
        }
        $nbTerrains = $event->getNbrFields();

        $matchs = RencontreUtils::generateRencontresAllerRetour($tabIdsParticipations, $event);
        foreach ($matchs as $match) {
            $em->persist($match);
        }

        if ($nbTerrains > floor(sizeof($tabIdsParticipations) / 2)) {
            $nbTerrains = floor(sizeof($tabIdsParticipations) / 2);
        }
        RencontreUtils::affectationTerrains($matchs, $nbTerrains, $event);
        $em->flush();
        return $this->redirectToRoute('admin_see_planning_meets', [
            "idEvent" => $idEvent
        ]);
    }


    /**
     * @Route("/poules/{idEvent}", name="affectation_poules", requirements={"idEvent": "\d+"})
     */
    function affectationPoule($idEvent, Request $request, EntityManagerInterface $em)
    {
        $nbPoule = $request->request->get('nbPoules');
        $participations = $em->getRepository(Participation::class)->findParticipationInAnEventSimple($idEvent);
        dump($participations);
        $count = sizeof($participations) / $nbPoule;
        for ($i = 0; $i < $nbPoule; $i++) {
            $poules[] = array_slice($participations, 0, $count);

            array_splice($participations, 0, $count);

            dump($participations);
        }

        dump($poules);
        $k = 'A';
        foreach ($poules as $poule) {
            for ($j = 0; $j < sizeof($poule); $j++) {
                $poule[$j]->setPoule($k);
                $em->persist($poule[$j]);
            }
            $k++;
        }
        $em->flush();
        return $this->redirectToRoute('admin_create_meet', ['idEvent' => $idEvent]);
    }

    function array_move_elem($array, $from, $to)
    {
        if ($from == $to) {
            return $array;
        }
        $c = count($array);
        if (($c > $from) and ($c > $to)) {
            if ($from < $to) {
                $f = $array[$from];
                for ($i = $from; $i < $to; $i++) {
                    $array[$i] = $array[$i + 1];
                }
                $array[$to] = $f;
            } else {
                $f = $array[$from];
                for ($i = $from; $i > $to; $i--) {
                    $array[$i] = $array[$i - 1];
                }
                $array[$to] = $f;
            }

        }
        return $array;
    }

    /**
     * Création phase 2
     */

    /**
     * Fonction créant automatiquement la phase du format Type 2 pour des poules de 4 à 7
     * @Route("/creationPhase2/{idEvent}", name="creation_phase2", requirements={"idEvent": "\d+"})
     */
    public function creationPhase2Type2Poule4a6($idEvent, ParticipationRepository $pr, EntityManagerInterface $em)
    {
        $participations = $pr->findParticipationInAnEventSimple($idEvent);
        $event = $em->getRepository(Event::class)->find($idEvent);
        $round = $em->getRepository(Round::class)->findOneBy(['name' => '1/2 finale']);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn() + 1);

        $em->persist($event1);

        usort($participations, function ($a, $b) {
            $ad = $a->getPointsClassement();
            $bd = $b->getPointsClassement();
            if ($ad == $bd) {
                return 0;
            } else {
                return $ad > $bd ? -1 : 1;
            }
        });        for ($i = 0; $i < 4; $i++) {
            $participation = new Participation();
            $participation->setParticipant($participations[$i]->getParticipant());
            $participation->setEvent($event1);
            $em->persist($participation);
        }

        $em->flush();
        return $this->redirectToRoute('admin_edit_event', ["id" => $event1->getId()]);
    }

    /**
     * fonction qui crée les rencontres de la 1/2 finale pour 4 à 6 poules
     * @Route("/rencontreDemiFinale/{idEvent}", name="creation_rencontres_demi_finale4_6", requirements={"idEvent": "\d+"})
     */
    public function creerRencontresDemiFinale($idEvent, EntityManagerInterface $em)
    {
        $participations = $em->getRepository(Participation::class)->findParticipationInAnEventSimple($idEvent);
        $event = $em->getRepository(Event::class)->find($idEvent);
        $nbTerrains = $event->getNbrFields();

        $matchs = RencontreUtils::creerDemiFinalePoule4($participations, $event);

        foreach ($matchs as $match) {
            $em->persist($match);
        }
        if ($nbTerrains > floor(sizeof($participations) / 2)) {
            $nbTerrains = floor(sizeof($participations) / 2);
        }
        RencontreUtils::affectationTerrains($matchs, $nbTerrains, $event);
        $em->flush();
        return $this->redirectToRoute('admin_see_planning_meets', [
            "idEvent" => $idEvent
        ]);

    }

    /**
     * fonction qui permet de créer une finale poule 4 à 6
     * @Route("/creationFinale/{idEvent}", name="creation_finale_4_6", requirements={"idEvent": "\d+"})
     */
    public function creerFinale4_6($idEvent, EntityManagerInterface $em)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $matchs = $em->getRepository(Match::class)->findMatchesWithAnEvent($event);
        $round = $em->getRepository(Round::class)->findOneBy(["name" => "Finale"]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn());
        $em->persist($event1);

        for ($i = 0; $i < 2; $i++) {
            $participation = new Participation();
            $participation->setEvent($event1);
            $participation->setParticipant($matchs[$i]->getWinner()->getParticipant());
            $em->persist($participation);
        }
        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ["id" => $event1->getId()]);
    }

    /**
     * fonction qui crée la finale pour 4 à 6 poules
     * @Route("/rencontre1vs1/{idEvent}", name="creation_rencontre_1vs1", requirements={"idEvent": "\d+"})
     */
    public function creerRencontre1vs1($idEvent, EntityManagerInterface $em)
    {
        $participations = $em->getRepository(Participation::class)->findParticipationInAnEventSimple($idEvent);
        $event = $em->getRepository(Event::class)->find($idEvent);
        $nbTerrains = $event->getNbrFields();
        $matchs = [];

        $match = RencontreUtils::creerMatch1vs1($participations, $event);

        $em->persist($match);
        $matchs[] = $match;

        if ($nbTerrains > floor(sizeof($participations) / 2)) {
            $nbTerrains = floor(sizeof($participations) / 2);
        }
        RencontreUtils::affectationTerrains($matchs, $nbTerrains,$event);
        $em->flush();
        return $this->redirectToRoute('admin_see_planning_meets', [
            "idEvent" => $idEvent
        ]);

    }

    /**
     * fonction qui permet de créer event de 3emeplace poule 4 à 6
     * @Route("/creation3emePlace/{idEvent}", name="creation_3eme_place_4_6", requirements={"idEvent": "\d+"})
     */
    public function creer3emePlace4_6($idEvent, EntityManagerInterface $em)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $matchs = $em->getRepository(Match::class)->findMatchesWithAnEvent($event);
        $round = $em->getRepository(Round::class)->findOneBy(["name" => "3ème place"]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn());
        $em->persist($event1);

        for ($i = 0; $i < 2; $i++) {
            $participation = new Participation();
            $participation->setEvent($event1);
            $participation->setParticipant($matchs[$i]->getLooser()->getParticipant());
            $em->persist($participation);
        }
        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ["id" => $event1->getId()]);
    }

    /**
     * fonction qui permet de créer event de 5emeplace
     * @Route("/creation5emePlace/{idEvent}", name="creation_5eme_place_6_7", requirements={"idEvent": "\d+"})
     */
    public function creer5emePlace6_7($idEvent, EntityManagerInterface $em)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $participations = $em->getRepository(Participation::class)->findParticipationInAnEventSimple($idEvent);
        $round = $em->getRepository(Round::class)->findOneBy(["name" => "5ème place"]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn());
        $em->persist($event1);

        usort($participations, function ($a, $b) {
            $ad = $a->getPointsClassement();
            $bd = $b->getPointsClassement();
            if ($ad == $bd) {
                return 0;
            } else {
                return $ad > $bd ? -1 : 1;
            }
        });
        $k = 4;
        for ($i = 0; $i < 2; $i++) {

            $participation = new Participation();
            $participation->setEvent($event1);
            $participation->setParticipant($participations[$k]->getParticipant());
            $em->persist($participation);
            $k++;
        }
        if (sizeof($participations) === 7) {
            $participations[sizeof($participations) - 1]->setPositionClassement(7);
        }
        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ["id" => $event1->getId()]);
    }

    /**
     * fonction qui permet de créer event de 3emeplace
     * @Route("/creation3emePlace7/{idEvent}", name="creation_3eme_place_6_7", requirements={"idEvent": "\d+"})
     */
    public function creer3emePlace6_7($idEvent, EntityManagerInterface $em)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $participations = $em->getRepository(Participation::class)->findParticipationInAnEventSimple($idEvent);
        $round = $em->getRepository(Round::class)->findOneBy(["name" => "3ème place"]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn());
        $em->persist($event1);

        usort($participations, function ($a, $b) {
            $ad = $a->getPointsClassement();
            $bd = $b->getPointsClassement();
            if ($ad == $bd) {
                return 0;
            } else {
                return $ad > $bd ? -1 : 1;
            }
        });
        $k = 2;
        for ($i = 0; $i < 2; $i++) {
            $participation = new Participation();
            $participation->setEvent($event1);
            $participation->setParticipant($participations[$k]->getParticipant());
            $em->persist($participation);
            $k++;
        }

        $em->flush();
        return $this->redirectToRoute('admin_edit_event', ["id" => $event1->getId()]);
    }

    /**
     * fonction qui permet de créer une finale poule 7
     * @Route("/creationFinale7/{idEvent}", name="creation_finale_7", requirements={"idEvent": "\d+"})
     */
    public function creerFinale7($idEvent, EntityManagerInterface $em)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $participations = $em->getRepository(Participation::class)->findParticipationInAnEventSimple($idEvent);
        $round = $em->getRepository(Round::class)->findOneBy(["name" => "Finale"]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn());
        $em->persist($event1);

        usort($participations, function ($a, $b) {
            $ad = $a->getPointsClassement();
            $bd = $b->getPointsClassement();
            if ($ad == $bd) {
                return 0;
            } else {
                return $ad > $bd ? -1 : 1;
            }
        });

        for ($i = 0; $i < 2; $i++) {
            $participation = new Participation();
            $participation->setEvent($event1);
            $participation->setParticipant($participations[$i]->getParticipant());
            $em->persist($participation);
        }
        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ["id" => $event1->getId()]);
    }

    /**
     * fonction qui permet de créer 1/2 finales des poules
     * @Route("/creationDemiFinale2Poule8/{idEvent}", name="creation_finale_7", requirements={"idEvent": "\d+"})
     */
    public function creerDemiFinale2poules8($idEvent, EntityManagerInterface $em, ParticipationRepository $pr)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $round = $em->getRepository(Round::class)->findOneBy(["name" => "1/2 Finale"]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn()+1);
        $em->persist($event1);

        $poules = $pr->nbrPoules($idEvent);

        for ($i = 0; $i < sizeof($poules); $i++) {
            $participationsPoule[] = $pr->getParPoules($idEvent, $poules[$i]->getPoule());
        }
        for($j=0;$j<sizeof($poules); $j++){
            //Etabli le classement par nbr de points
            usort($participationsPoule[$j], function ($a, $b) {
                $ad = $a->getPointsClassement();
                $bd = $b->getPointsClassement();
                if ($ad == $bd) {
                    return 0;
                } else {
                    return $ad > $bd ? -1 : 1;
                }
            });
        }

        for ($i = 0; $i < 2; $i++) {
            $participation = new Participation();
            $participation->setEvent($event1);
            $participation->setParticipant($participationsPoule[$i]->getParticipant());
            $em->persist($participation);
        }
        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ["id" => $event1->getId()]);
    }
}
