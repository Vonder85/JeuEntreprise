<?php

namespace App\Controller;

use App\Data\EventCriteria;
use App\Data\RechercheCriteria;
use App\Entity\Athlet;
use App\Entity\Category;
use App\Entity\Company;
use App\Entity\Competition;
use App\Entity\Discipline;
use App\Entity\Event;
use App\Entity\Match;
use App\Entity\Participant;
use App\Entity\Participation;
use App\Entity\Rencontre;
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
            $nbMatchs = $request->request->get('nbMatchs');
            if ($nbMatchs) {
                $event->setNbMatchsMulti($nbMatchs);
            }
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
    public function getEvents(EntityManagerInterface $em, Request $req)
    {
        $criteria = $this->buildCriteria($req, $em);
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

    public static function buildCriteria(Request $req, EntityManagerInterface $em)
    {
        $criteria = new EventCriteria();
        if ($req->query->get('search')) {
            $criteria->setSearch($req->query->get('search'));
        }
        if ($req->query->get('competition') != "" && $req->query->get('competition') != null) {
            if ($req->query->get('competition') == "All") {

            } else {
                $idCompetition = $req->query->get('competition');
                $competition = $em->getRepository(Competition::class)->find($idCompetition);
                $criteria->setCompetition($competition);
            }
        }
        if ($req->query->get('discipline') != "" && $req->query->get('discipline') != null) {
            if ($req->query->get('discipline') == "All") {

            } else {
                $idDiscipline = $req->query->get('discipline');
                $discipline = $em->getRepository(Discipline::class)->find($idDiscipline);
                $criteria->setDiscipline($discipline);
            }
        }
        if ($req->query->get('gender') != "" && $req->query->get('gender') != null) {
            if ($req->query->get('gender') == "All") {

            } else {
                $criteria->setGender($req->query->get('gender'));
            }
        }

        if ($req->query->get('category') != "" && $req->query->get('category') != null) {
            if ($req->query->get('category') == "All") {

            } else {
                $idCategory = $req->query->get('category');
                $category = $em->getRepository(Category::class)->find($idCategory);
                $criteria->setCategory($category);
            }
        }
        if ($req->query->get('type') != "" && $req->query->get('type') != null) {
            if ($req->query->get('type') == "All") {

            } else {
                $idType = $req->query->get('type');
                $type = $em->getRepository(Type::class)->find($idType);
                $criteria->setType($type);
            }
        }

        if ($req->query->get('round') != "" && $req->query->get('round') != null) {
            if ($req->query->get('round') == "All") {

            } else {
                $idRound = $req->query->get('round');
                $round = $em->getRepository(Round::class)->find($idRound);
                $criteria->setRound($round);
            }
        }

        return $criteria;
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
        $teamForm = $this->createForm(TeamType::class, $team);

        $teamForm->handleRequest($req);
        if ($teamForm->isSubmitted() && $teamForm->isValid()) {

            $em->persist($teamCreated);
            $em->flush();
            $this->addFlash('success', 'Equipe Modifiée');
            return $this->redirectToRoute('admin_teams');
        }


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
        $teamForm = $this->createForm(TeamType::class, $team);

        $athletsTeam = $em->getRepository(TeamCreated::class)->athletinTeam($id);


        return $this->render('admin/edit/team.html.twig', [
            "teamCreatedForm" => $teamCreatedForm->createView(),
            "team" => $team,
            "athletsTeam" => $athletsTeam,
            'teamForm' => $teamForm->createView()
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
        $nbrPoules = $em->getRepository(Participation::class)->nbrPoules($id);
        $participations = $em->getRepository(Participation::class)->findParticipationInAnEventSimple($id);
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
            foreach ($participations as $particip) {
                if ($participation->getParticipant() == $particip->getParticipant()) {
                    $this->addFlash('info', "Participant déjà ajouté.");
                    return $this->redirectToRoute("admin_edit_event", ["id" => $id]);
                }
            }
            $participation->setEvent($event);
            $em->persist($participation);
            $em->flush();
            $this->addFlash('success', 'Participant ajouté');
        }

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Evènement modifié');
            return $this->redirectToRoute('admin_edit_event', ['id' => $id]);
        }
        $participants = $em->getRepository(Participation::class)->findParticipationInAnEvent($id);

        return $this->render('admin/edit/event.html.twig', [
            'eventForm' => $eventForm->createView(),
            'event' => $event,
            "participants" => $participants,
            "participationForm" => $participationForm->createView(),
            "participationAthletForm" => $participationAthletForm->createView(),
            "nbrPoules" => $nbrPoules
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
    public function seePlanningMeets($idEvent, ParticipationRepository $pr, EntityManagerInterface $em)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $matchs = $em->getRepository(Match::class)->findMatchesWithAnEvent($event);
        if (sizeof($matchs) === 0) {
            $this->addFlash("info", "Les rencontres ne sont pas encore créées, veuillez les créer.");
            return $this->redirectToRoute('admin_edit_event', ['id' => $idEvent]);
        }

        $participations = $pr->findParticipationInAnEventSimple($idEvent);
        $roundPhase1 = $em->getRepository(Round::class)->findOneBy(['name' => 'Phase de poules 1']);
        $participationsDebut = $pr->findParticipationsWithAnEventAndRound($event->getName(), $roundPhase1, $event->getCompetition());
        $totalParticipationsDebut = sizeof($participationsDebut);
        $nbrPoules = $pr->nbrPoules($idEvent);


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
        dump($matchs);
        return $this->render('admin/planning.html.twig', [
            "participants" => $participations,
            "matchs" => $matchs,
            "nbrPoules" => $nbrPoules,
            "event" => $event,
            "totalParticipationsDebut" => $totalParticipationsDebut
        ]);
    }


    /**
     * @param $tabIdsParticipations
     * @param $event
     * @param EntityManagerInterface $em
     * Function establish list of meets
     * @Route("CreerRencontres/{idEvent}", name="creer_rencontre", requirements={"idEvent": "\d+"})
     */
    public function creerRencontres($idEvent, EntityManagerInterface $em, Request $request)
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

        $nbTerrains = RencontreUtils::nbrTerrains($nbTerrains, $tabIdsParticipations);
        $aPartir = $request->request->get('aPartir');

        RencontreUtils::affectationTerrains($matchs, $nbTerrains, $event, (integer)$aPartir);
        $em->flush();
        return $this->redirectToRoute('admin_see_planning_meets', [
            "idEvent" => $idEvent
        ]);
    }

    /**
     * fonction qui crée les rencontres des poules
     * @Route("/creerMatchsPoules/{idEvent}", name="creer_matchs_poules", requirements={"idEvent": "\d+"})
     */
    function creerRencontresPoules($idEvent, ParticipationRepository $pr, EntityManagerInterface $em, Request $request)
    {
        $poules = $pr->nbrPoules($idEvent);
        $totalParticipants = $pr->findParticipationInAnEventSimple($idEvent);
        for ($i = 0; $i < sizeof($poules); $i++) {
            $participations[] = $pr->getParPoules($idEvent, $poules[$i]->getPoule());
        }

        $event = $em->getRepository(Event::class)->find($idEvent);

        $nbTerrainsPouleA = $request->request->get('poule1');
        $nbTerrainsPouleB = $request->request->get('poule2');
        $nbTerrainsPouleC = $request->request->get('poule3');
        $nbTerrainsPouleD = $request->request->get('poule4');
        $nbTerrainsPouleE = $request->request->get('poule5');
        $aPartirA = $request->request->get('aPartir1');
        $aPartirB = $request->request->get('aPartir2');
        $aPartirC = $request->request->get('aPartir3');
        $aPartirD = $request->request->get('aPartir4');
        $aPartirE = $request->request->get('aPartir5');
        $matchs = RencontreUtils::generateRencontresPoules($participations, $event);


        foreach ($matchs as $match) {
            $em->persist($match);
        }
        $em->flush();


        if ($nbTerrainsPouleA > 0) {
            $matchsA = $em->getRepository(Match::class)->findMatchesWithAnEventAndPoule($event, 'A');
            $participations = $em->getRepository(Participation::class)->getNbrParticipantsWithEventAndPoule($event, 'A');
            $nbTerrainsPouleA = RencontreUtils::nbrTerrains($nbTerrainsPouleA, $participations);
            RencontreUtils::affectationTerrainsPoules($matchsA, $nbTerrainsPouleA, $event, $aPartirA);
        }
        if ($nbTerrainsPouleB > 0) {
            $matchsB = $em->getRepository(Match::class)->findMatchesWithAnEventAndPoule($event, 'B');
            $participations = $em->getRepository(Participation::class)->getNbrParticipantsWithEventAndPoule($event, 'B');
            $nbTerrainsPouleB = RencontreUtils::nbrTerrains($nbTerrainsPouleB, $participations);
            RencontreUtils::affectationTerrainsPoules($matchsB, $nbTerrainsPouleB, $event, $aPartirB);
        }
        if ($nbTerrainsPouleC > 0) {
            $matchsC = $em->getRepository(Match::class)->findMatchesWithAnEventAndPoule($event, 'C');
            $participations = $em->getRepository(Participation::class)->getNbrParticipantsWithEventAndPoule($event, 'C');
            $nbTerrainsPouleC = RencontreUtils::nbrTerrains($nbTerrainsPouleC, $participations);
            RencontreUtils::affectationTerrains($matchsC, $nbTerrainsPouleC, $event, $aPartirC);
        }
        if ($nbTerrainsPouleD > 0) {
            $matchsD = $em->getRepository(Match::class)->findMatchesWithAnEventAndPoule($event, 'D');
            $participations = $em->getRepository(Participation::class)->getNbrParticipantsWithEventAndPoule($event, 'D');
            $nbTerrainsPouleD = RencontreUtils::nbrTerrains($nbTerrainsPouleD, $participations);
            RencontreUtils::affectationTerrains($matchsD, $nbTerrainsPouleD, $event, $aPartirD);
        }
        if ($nbTerrainsPouleE > 0) {
            $matchsE = $em->getRepository(Match::class)->findMatchesWithAnEventAndPoule($event, 'E');
            $participations = $em->getRepository(Participation::class)->getNbrParticipantsWithEventAndPoule($event, 'E');
            $nbTerrainsPouleE = RencontreUtils::nbrTerrains($nbTerrainsPouleE, $participations);
            RencontreUtils::affectationTerrains($matchsE, $nbTerrainsPouleE, $event, $aPartirE);
        }

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
    public function creerRencontresAllerRetour($idEvent, EntityManagerInterface $em, Request $request)
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
        $aPartir = $request->request->get('aPartir');
        $matchs = RencontreUtils::generateRencontresAllerRetour($tabIdsParticipations, $event);
        foreach ($matchs as $match) {
            $em->persist($match);
        }

        $nbTerrains = RencontreUtils::nbrTerrains($nbTerrains, $tabIdsParticipations);
        RencontreUtils::affectationTerrains($matchs, $nbTerrains, $event, $aPartir);
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
        $count = sizeof($participations) / $nbPoule;
        $event = $em->getRepository(Event::class)->find($idEvent);
        $roundPhase1 = $em->getRepository(Round::class)->findOneBy(['name' => 'Phase de poules 1']);
        $participationsDebut = $em->getRepository(Participation::class)->findParticipationsWithAnEventAndRound($event->getName(), $roundPhase1, $event->getCompetition());

        if (sizeof($participationsDebut) == 21 && sizeof($participations) == 9) {
            $poules = RencontreUtils::affectationPoulesConsolante9phase3($nbPoule, $participations, $count);
            $k = 'A';
            foreach ($poules as $poule) {
                for ($j = 0; $j < sizeof($poule); $j++) {
                    $poule[$j][0]->setPoule($k);
                    $em->persist($poule[$j][0]);
                }

                $k++;
            }
        } elseif (($event->getRound()->getName() === "Tournoi principal" || $event->getRound()->getName() === "Tournoi consolante") && $event->getPhase() === 3 && (sizeof($participations) == 6 || sizeof($participations) == 8 || sizeof($participations) == 9 || sizeof($participations) == 12)) {
            $poules = RencontreUtils::affectationPoulesConsolante($nbPoule, $participations, $count);

            $k = 'A';
            foreach ($poules as $poule) {
                for ($j = 0; $j < sizeof($poule); $j++) {
                    $poule[$j][0]->setPoule($k);
                    $em->persist($poule[$j][0]);
                }

                $k++;
            }
        } elseif ($event->getRound()->getName() === "Tournoi consolante" && $event->getPhase() === 2) {
            $poules = RencontreUtils::affectationPoulesConsolante($nbPoule, $participations, $count);

            $k = 'A';
            foreach ($poules as $poule) {
                for ($j = 0; $j < sizeof($poule); $j++) {
                    $poule[$j][0]->setPoule($k);
                    $em->persist($poule[$j][0]);
                }

                $k++;
            }
        } else {
            $poules = RencontreUtils::affectationPoule($nbPoule, $participations, $count);

            $k = 'A';
            foreach ($poules as $poule) {
                for ($j = 0; $j < sizeof($poule); $j++) {
                    $poule[$j]->setPoule($k);
                    $em->persist($poule[$j]);
                }

                $k++;
            }
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
    public
    function creationPhase2Type2Poule4a6($idEvent, ParticipationRepository $pr, EntityManagerInterface $em, MatchRepository $mr)
    {
        $participations = $pr->findParticipationInAnEventSimple($idEvent);
        $event = $em->getRepository(Event::class)->find($idEvent);
        $round = $em->getRepository(Round::class)->findOneBy(['name' => '1/2 finale']);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn() + 1);

        $em->persist($event1);

        $participations = (new \App\Utils\EventUtils($mr))->classerParPoints($participations);
        for ($i = 0; $i < 4; $i++) {
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
     * @Route("/rencontreDemiFinale/{idEvent}", name="creation_rencontres_demi_finale", requirements={"idEvent": "\d+"})
     */
    public
    function creerRencontresDemiFinale($idEvent, EntityManagerInterface $em, Request $request)
    {
        $participations = $em->getRepository(Participation::class)->findParticipationInAnEventSimple($idEvent);
        $event = $em->getRepository(Event::class)->find($idEvent);
        $roundPhase1 = $em->getRepository(Round::class)->findOneBy(['name' => 'Phase de poules 1']);
        $participationsDebut = $em->getRepository(Participation::class)->findParticipationsWithAnEventAndRound($event->getName(), $roundPhase1, $event->getCompetition());
        $nbTerrains = $event->getNbrFields();
        $aPartir = $request->request->get('aPartir');
        $nbTerrains = RencontreUtils::nbrTerrains($nbTerrains, $participations);

        if (sizeof($participations) === 8 && ($event->getRound()->getName() == "1/4 finale consolante" || $event->getRound()->getName() == "1/4 finale") && (sizeof($participationsDebut) === 16 || sizeof($participationsDebut) >= 18)) {
            $matchs = RencontreUtils::creerDemiFinale($participations, $event);
            foreach ($matchs as $match) {
                $em->persist($match);
            }
            RencontreUtils::affectationTerrains($matchs, $nbTerrains, $event, $aPartir);
        } elseif (sizeof($participations) === 12 && (sizeof($participationsDebut) >= 18) && $event->getPhase() == 3) {
            $matchs = RencontreUtils::rencontresDemiFinales3phases1819et20($participations, $event);
            foreach ($matchs as $match) {
                $em->persist($match);
            }
            RencontreUtils::affectationTerrains($matchs, $nbTerrains, $event, $aPartir);
        } elseif (sizeof($participations) === 8 || sizeof($participations) === 12) {
            $parts = array_chunk($participations, 4);
            foreach ($parts as $part) {
                $matchs[] = RencontreUtils::creerDemiFinale($part, $event);
            }
            for ($j = 0; $j < sizeof($matchs); $j++) {
                for ($i = 0; $i < 2; $i++) {
                    $em->persist($matchs[$j][$i]);
                }
            }
            //Afectation des terrains en fonction du nombre de demi finales
            for ($k = 0; $k < sizeof($parts); $k++) {
                RencontreUtils::affectationTerrains($matchs[$k], $nbTerrains, $event, $aPartir);
            }
        } elseif ($event->getRound()->getName() == "1/4 finale consolante") {
            $matchs = RencontreUtils::creerQuartFinale3Poules($participations, $event);
            foreach ($matchs as $match) {
                $em->persist($match);
            }
            RencontreUtils::affectationTerrains($matchs, $nbTerrains, $event, $aPartir);
        } elseif (sizeof($participations) === 16) {
            $parts = array_chunk($participations, 8);
            foreach ($parts as $part) {
                $matchs[] = RencontreUtils::creerDemiFinale($part, $event);
            }
            for ($j = 0; $j < sizeof($matchs); $j++) {
                for ($i = 0; $i < sizeof($matchs[$j]); $i++) {
                    $em->persist($matchs[$j][$i]);
                }
            }
            //Afectation des terrains en fonction du nombre de demi finales
            for ($k = 0; $k < sizeof($parts); $k++) {
                RencontreUtils::affectationTerrains($matchs[$k], $nbTerrains, $event, $aPartir);
            }
        } else {
            $matchs = RencontreUtils::creerDemiFinale($participations, $event);
            foreach ($matchs as $match) {
                $em->persist($match);
            }
            RencontreUtils::affectationTerrains($matchs, $nbTerrains, $event, $aPartir);
        }

        $em->flush();
        return $this->redirectToRoute('admin_see_planning_meets', [
            "idEvent" => $idEvent
        ]);

    }

    /**
     * fonction qui permet de créer les matchs de phase finale
     * @Route("/creationMatchPhaseFinale/{idEvent}", name="creation_phase_finale", requirements={"idEvent": "\d+"})
     */
    public
    function creerPhasesFinale($idEvent, EntityManagerInterface $em, Request $request, MatchRepository $mr)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $matchs = $em->getRepository(Match::class)->findMatchesWithAnEvent($event);
        $roundName = $request->request->get('round');
        $round = $em->getRepository(Round::class)->findOneBy(["name" => $roundName]);
        $roundPhase1 = $em->getRepository(Round::class)->findOneBy(['name' => 'Phase de poules 1']);
        $participationsDebut = $em->getRepository(Participation::class)->findParticipationsWithAnEventAndRound($event->getName(), $roundPhase1, $event->getCompetition());
        $totalParticipationsDebut = sizeof($participationsDebut);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn());
        $em->persist($event1);
        if ($totalParticipationsDebut == 15 && $event->getRound()->getName() == "1/4 finale consolante") {
            $participations = RencontreUtils::creerMatchsPhaseFinale($matchs, $event1, $roundName);


            $eventPhase1 = $em->getRepository(Event::class)->findEventWithEventNameRoundNameAndCompetition($event->getName(), $roundPhase1->getName(), $event->getCompetition());
            $poules = $em->getRepository(Participation::class)->nbrPoules($eventPhase1[0]->getId());
            //Récupération des participations par poules de la phase1
            for ($i = 0; $i < sizeof($poules); $i++) {
                $participationsPoule[] = $em->getRepository(Participation::class)->getParPoules($eventPhase1[0]->getId(), $poules[$i]->getPoule());
            }
            //Classer les participants dans poules par ordre de points
            $participationsPoule = (new \App\Utils\EventUtils($mr))->classerParPointsPoules($participationsPoule, $poules);
            //Récupérer tous le 3ème de chaque poule
            $les3emes = [];
            for ($i = 0; $i < sizeof($participationsPoule); $i++) {
                $les3emes[] = $participationsPoule[$i][2];
            }
            //Classer les 3emes par points
            $les3emes = (new \App\Utils\EventUtils($mr))->classerParPoints($les3emes);
            dump($participationsPoule);

            $participation = new Participation();
            $participation->setEvent($event1);
            $participation->setParticipant($les3emes[sizeof($les3emes) - 1]->getParticipant());
            array_push($participations, $participation);

            foreach ($participations as $participation) {
                $em->persist($participation);
            }
        } elseif (($totalParticipationsDebut === 16 || $totalParticipationsDebut === 17 || $totalParticipationsDebut === 15 || $totalParticipationsDebut === 18 || $totalParticipationsDebut === 19 || $totalParticipationsDebut === 20 || $totalParticipationsDebut === 21) && ($event->getRound()->getName() == "1/4 finale consolante" || $event->getRound()->getName() == "1/2 finale consolante")) {
            $participations = RencontreUtils::creerMatchsPhaseFinaleConsolanteAPartir15Equipes($matchs, $event1, $roundName);
            foreach ($participations as $participation) {
                $em->persist($participation);
            }
        } else {
            $participations = RencontreUtils::creerMatchsPhaseFinale($matchs, $event1, $roundName);

            foreach ($participations as $participation) {
                $em->persist($participation);
            }
            $participations = [];
            //Récupération des 5eme de chaque poule de la premiere phase
            if (($totalParticipationsDebut == 20 || $totalParticipationsDebut == 21) && $event->getRound()->getName() == "1/4 finale consolante") {

                $eventPhasePoule = $em->getRepository(Event::class)->findEventWithEventNameRoundNameAndCompetition($event->getName(), $roundPhase1->getName(), $event->getCompetition());
                $poules = $em->getRepository(Participation::class)->nbrPoules($eventPhasePoule[0]->getId());
                //Récupération des participations par poules de la phase1
                for ($i = 0; $i < sizeof($poules); $i++) {
                    $participationsPoule[] = $em->getRepository(Participation::class)->getParPoules($eventPhasePoule[0]->getId(), $poules[$i]->getPoule());
                }
                $participationsPoule = (new \App\Utils\EventUtils($mr))->classerParPointsPoules($participationsPoule, $poules);
                $participations[] = RencontreUtils::participations5emeChaquePoule($participationsPoule);

                foreach ($participations as $particip) {
                    for ($i = 0; $i < sizeof($particip); $i++) {
                        $participation = new Participation();
                        $participation->setEvent($event1);
                        $participation->setParticipant($particip[$i]->getParticipant());
                        $em->persist($participation);
                    }
                }
            }
        }

        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ["id" => $event1->getId()]);
    }

    /**
     * fonction qui crée les participations places classement a partir de 15equipes
     * @Route("/rencontreplaceclassement/{idEvent}", name="creation_rencontres_places_classment", requirements={"idEvent": "\d+"})
     */
    public function creerRencontres357emeplace($idEvent, EntityManagerInterface $em, Request $request)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $matchs = $em->getRepository(Match::class)->findMatchesWithAnEvent($event);
        $roundName = $request->request->get('round');
        $round = $em->getRepository(Round::class)->findOneBy(['name' => $roundName]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn());
        $em->persist($event1);

        if ($roundName === "7ème place") {
            array_splice($matchs, 0, 2);
            $participations = RencontreUtils::recupParticipationsAvecLoosersde2Matchs($matchs, $event1);
        } elseif ($roundName === "5ème place") {
            array_splice($matchs, 0, 2);
            $participations = RencontreUtils::recupParticipationsAvecWinnersde2Matchs($matchs, $event1);
        } elseif ($roundName === "3ème place") {
            $participations = RencontreUtils::recupParticipationsAvecLoosersde2Matchs($matchs, $event1);
        } elseif ($roundName === "9ème place") {
            //On garde juste les deux derniers matchs
            array_splice($matchs, 0, 4);
            $participations = RencontreUtils::recupParticipationsAvecWinnersde2Matchs($matchs, $event1);
        } elseif ($roundName === "11ème place") {
            //On garde juste les deux derniers matchs
            array_splice($matchs, 0, 4);
            $participations = RencontreUtils::recupParticipationsAvecLoosersde2Matchs($matchs, $event1);
        }
        foreach ($participations as $participation) {
            $em->persist($participation);
        }

        $em->flush();
        return $this->redirectToRoute('admin_edit_event', [
            "id" => $event1->getId()
        ]);

    }

    /**
     * fonction qui crée les rencontres de la 1/4 finale
     * @Route("/rencontreQuartFinale/{idEvent}", name="creation_rencontres_quartFinale", requirements={"idEvent": "\d+"})
     */
    public
    function creerRencontresQuartFinale($idEvent, EntityManagerInterface $em, Request $request)
    {
        $participations = $em->getRepository(Participation::class)->findParticipationInAnEventSimple($idEvent);
        $event = $em->getRepository(Event::class)->find($idEvent);
        $nbTerrains = $event->getNbrFields();
        $aPartir = $request->request->get('aPartir');
        $nbTerrains = RencontreUtils::nbrTerrains($nbTerrains, $participations);


        $matchs[] = RencontreUtils::creerQuartFinale($participations, $event);

        for ($j = 0; $j < sizeof($matchs); $j++) {
            for ($i = 0; $i < 4; $i++) {
                $em->persist($matchs[$j][$i]);
            }
        }
        //Afectation des terrains en fonction du nombre de demi finales
        for ($k = 0; $k < sizeof($matchs); $k++) {
            RencontreUtils::affectationTerrains($matchs[$k], $nbTerrains, $event, $aPartir);
        }

        $em->flush();
        return $this->redirectToRoute('admin_see_planning_meets', [
            "idEvent" => $idEvent
        ]);

    }

    /**
     * fonction qui crée les rencontres de la 1/4 finale a partir15equipes
     * @Route("/rencontreQuartFinaleAPartir15equipes/{idEvent}", name="creation_rencontres_quartFinale_15equipes", requirements={"idEvent": "\d+"})
     */
    public
    function creerRencontresQuartFinaleAPartir15equipes($idEvent, EntityManagerInterface $em, Request $request)
    {
        $participations = $em->getRepository(Participation::class)->findParticipationInAnEventSimple($idEvent);
        $event = $em->getRepository(Event::class)->find($idEvent);
        $nbTerrains = $event->getNbrFields();
        $aPartir = $request->request->get('aPartir');
        $nbTerrains = RencontreUtils::nbrTerrains($nbTerrains, $participations);

        $matchs[] = RencontreUtils::creerQuartFinaleAPArtir15Equipes($participations, $event);

        for ($j = 0; $j < sizeof($matchs); $j++) {
            for ($i = 0; $i < 4; $i++) {
                $em->persist($matchs[$j][$i]);
            }
        }
        //Afectation des terrains en fonction du nombre de demi finales
        for ($k = 0; $k < sizeof($matchs); $k++) {
            RencontreUtils::affectationTerrains($matchs[$k], $nbTerrains, $event, $aPartir);
        }

        $em->flush();
        return $this->redirectToRoute('admin_see_planning_meets', [
            "idEvent" => $idEvent
        ]);

    }

    /**
     * fonction qui crée la finale pour 4 à 6 poules
     * @Route("/rencontre1vs1/{idEvent}", name="creation_rencontre_1vs1", requirements={"idEvent": "\d+"})
     */
    public
    function creerRencontre1vs1($idEvent, EntityManagerInterface $em, Request $request)
    {
        $participations = $em->getRepository(Participation::class)->findParticipationInAnEventSimple($idEvent);
        $event = $em->getRepository(Event::class)->find($idEvent);
        $nbTerrains = $event->getNbrFields();
        $matchs = [];
        $aPartir = $request->request->get('aPartir');
        $match = RencontreUtils::creerMatch1vs1($participations, $event);

        $em->persist($match);
        $matchs[] = $match;

        $nbTerrains = RencontreUtils::nbrTerrains($nbTerrains, $participations);
        RencontreUtils::affectationTerrains($matchs, $nbTerrains, $event, $aPartir);
        $em->flush();
        return $this->redirectToRoute('admin_see_planning_meets', [
            "idEvent" => $idEvent
        ]);

    }

    /**
     * fonction qui permet de créer event de 3579emeplace
     * @Route("/creation3579emePlace/{idEvent}", name="creation_3579eme_place", requirements={"idEvent": "\d+"})
     */
    public
    function creer3579emePlace($idEvent, EntityManagerInterface $em, Request $request)
    {
        $roundDebut = $em->getRepository(Round::class)->findOneBy(['name' => 'Phase de poules 1']);
        $event = $em->getRepository(Event::class)->find($idEvent);
        $participationsDebut = $em->getRepository(Participation::class)->findParticipationsWithAnEventAndRound($event->getName(), $roundDebut, $event->getCompetition());
        $roundName = $request->request->get('round');

        if (sizeof($participationsDebut) === 5) {
            return $this->redirectToRoute('admin_creation_poule_3eme_place', ["idEvent" => $event->getId()]);
        }

        $matchs = $em->getRepository(Match::class)->findMatchesWithAnEvent($event);
        $round = $em->getRepository(Round::class)->findOneBy(["name" => $roundName]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn());
        $em->persist($event1);

        if ($roundName == '3ème place' || $roundName == '9ème place') {
            $participations = RencontreUtils::recupParticipationsAvecLoosersde2Matchs($matchs, $event1);
        } elseif ($roundName == '7ème place') {
            $participations = RencontreUtils::recupParticipationsAvecWinnersde2Matchs($matchs, $event1);
        }
        foreach ($participations as $participation) {
            $em->persist($participation);
        }
        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ["id" => $event1->getId()]);
    }

    /**
     * fonction qui permet de créer poule de 3emeplace
     * @Route("/creationPoule3emePlace/{idEvent}", name="creation_poule_3eme_place", requirements={"idEvent": "\d+"})
     */
    public
    function creerPoule3emePlace($idEvent, EntityManagerInterface $em, MatchRepository $mr)
    {
        $roundDebut = $em->getRepository(Round::class)->findOneBy(['name' => 'Phase de poules 1']);
        $event = $em->getRepository(Event::class)->find($idEvent);
        $participationsDebut = $em->getRepository(Participation::class)->findParticipationsWithAnEventAndRound($event->getName(), $roundDebut, $event->getCompetition());

        $matchs = $em->getRepository(Match::class)->findMatchesWithAnEvent($event);
        $round = $em->getRepository(Round::class)->findOneBy(["name" => "Poule de classement"]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn());
        $em->persist($event1);

        $participationsDebut = (new \App\Utils\EventUtils($mr))->classerParPoints($participationsDebut);

        $participations = RencontreUtils::creerPoule3emePlace($event1, $participationsDebut, $matchs);
        foreach ($participations as $participation) {
            $em->persist($participation);
        }
        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ["id" => $event1->getId()]);
    }

    /**
     * fonction qui permet de créer poule de 5emeplace
     * @Route("/creationPoule5emePlace/{idEvent}", name="creation_poule_5eme_place", requirements={"idEvent": "\d+"})
     */
    public
    function creerPoule5emePlace($idEvent, EntityManagerInterface $em, MatchRepository $mr, Request $request)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $roundName = $request->request->get('round');
        $participations = $em->getRepository(Participation::class)->findParticipationInAnEventSimple($idEvent);
        $round = $em->getRepository(Round::class)->findOneBy(["name" => $roundName]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn() + 1);
        $em->persist($event1);

        $participations = (new \App\Utils\EventUtils($mr))->classerParPoints($participations);

        //Récupération des derniers de la phase de poule
        $participations = RencontreUtils::creerPoule5emePlace($participations);

        foreach ($participations as $particip) {
            $participation = new Participation();
            $participation->setEvent($event1);
            $participation->setParticipant($particip->getParticipant());
            $em->persist($participation);
        }

        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ["id" => $event1->getId()]);
    }

    /**
     * fonction qui permet de créer event de 5emeplace
     * @Route("/creation3Et5emePlace/{idEvent}", name="creation_3Et5eme_place", requirements={"idEvent": "\d+"})
     */
    public
    function creer3Et5emePlace($idEvent, EntityManagerInterface $em, MatchRepository $mr, Request $request)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $roundName = $request->request->get('round');
        $participations = $em->getRepository(Participation::class)->findParticipationInAnEventSimple($idEvent);
        $round = $em->getRepository(Round::class)->findOneBy(["name" => $roundName]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn());
        $em->persist($event1);

        $participations = (new \App\Utils\EventUtils($mr))->classerParPoints($participations);

        $participations = RencontreUtils::creer3et5EmePlace($roundName, $event1, $participations);
        foreach ($participations as $participation) {
            $em->persist($participation);
        }
        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ["id" => $event1->getId()]);
    }

    /**
     * fonction qui permet de créer 1/ finales 18 equipes
     * @Route("/creationQuartFinales18equipes/{idEvent}", name="creation_quartfinales_18equipes", requirements={"idEvent": "\d+"})
     */
    public
    function creerQuartFinales18equipes($idEvent, EntityManagerInterface $em, MatchRepository $mr, ParticipationRepository $pr, Request $request)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $roundName = $request->request->get('round');
        $round = $em->getRepository(Round::class)->findOneBy(["name" => $roundName]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn() + 1);
        $event1->setPoule(false);
        $em->persist($event1);

        $poules = $pr->nbrPoules($idEvent);

        for ($i = 0; $i < sizeof($poules); $i++) {
            $participationsPoule[] = $pr->getParPoules($idEvent, $poules[$i]->getPoule());
        }

        //Etabli le classement par nbr de points
        $participationsPoule = (new \App\Utils\EventUtils($mr))->classerParPointsPoules($participationsPoule, $poules);
        $participations = [];
        if ($roundName === "1/4 finale consolante") {
            $participations = RencontreUtils::participationsQuartFinaleConsolante18equipes($participationsPoule, $event1);
        } else {

        }
        foreach ($participations as $participation) {
            $em->persist($participation);
        }

        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ['id' => $event1->getId()]);
    }

    /**
     * fonction qui permet de créer 1/2 finales des poules
     * @Route("/creationDemiFinales/{idEvent}", name="creation_demifinales", requirements={"idEvent": "\d+"})
     */
    public
    function creerDemiFinales($idEvent, EntityManagerInterface $em, MatchRepository $mr, ParticipationRepository $pr, Request $request)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $participations = $pr->findParticipationInAnEvent($idEvent);
        $roundName = $request->request->get('round');
        $round = $em->getRepository(Round::class)->findOneBy(["name" => $roundName]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn() + 1);
        $event1->setPoule(false);
        $em->persist($event1);

        $poules = $pr->nbrPoules($idEvent);

        for ($i = 0; $i < sizeof($poules); $i++) {
            $participationsPoule[] = $pr->getParPoules($idEvent, $poules[$i]->getPoule());
        }

        //Etabli le classement par nbr de points
        $participationsPoule = (new \App\Utils\EventUtils($mr))->classerParPointsPoules($participationsPoule, $poules);

        if (sizeof($participations) !== 14) {
            $participationsDemi = RencontreUtils::creerPhasesDemiFinale($participationsPoule, $event1, $poules, $participations);

            foreach ($participationsDemi as $particip) {
                $em->persist($particip);
            }
        } elseif (sizeof($poules) == 3 && sizeof($participations) === 14) {
            $participationsQuart = RencontreUtils::creerTournoiPrincipal($poules, $participationsPoule);

            foreach ($participationsQuart as $particip) {
                $participation = new Participation();
                $participation->setEvent($event1);
                $participation->setParticipant($particip->getParticipant());
                $em->persist($participation);
            }
        }

        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ['id' => $event1->getId()]);
    }

    /**
     * fonction qui permet de créer 1/2 finales consolante
     * @Route("/creationDemiFinaleConsolante/{idEvent}", name="creation_demifinale_consolante", requirements={"idEvent": "\d+"})
     */
    public
    function creerDemiFinaleConsolante($idEvent, EntityManagerInterface $em, MatchRepository $mr, ParticipationRepository $pr, Request $request)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $roundName = $request->request->get('round');
        $round = $em->getRepository(Round::class)->findOneBy(["name" => $roundName]);
        $participations = $pr->findParticipationInAnEventSimple($idEvent);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn() + 1);
        $em->persist($event1);

        $poules = $pr->nbrPoules($idEvent);

        for ($i = 0; $i < sizeof($poules); $i++) {
            $participationsPoule[] = $pr->getParPoules($idEvent, $poules[$i]->getPoule());
        }
        //Etabli le classement par nbr de points
        $participationsPoule = (new \App\Utils\EventUtils($mr))->classerParPointsPoules($participationsPoule, $poules);

        //Attribution 17eme place pour le dernier de la poule de 5
        if (sizeof($participations) === 17) {
            $dernier = array_splice($participationsPoule[0], sizeof($participationsPoule[0]) - 1, 1);
            $dernier[0]->setPositionClassement(17);
        } elseif (sizeof($participations) === 21) {
            $dernier = array_splice($participationsPoule[0], sizeof($participationsPoule[0]) - 1, 1);
            $dernier[0]->setPositionClassement(21);
        }
        if (sizeof($participations) == 19 || sizeof($participations) == 20 || sizeof($participations) == 21) {
            //Récupère les 3eme et 4eme
            $participations = RencontreUtils::participations3emeet4eme($participationsPoule, $event1);
        } else {
            //Récupérer les deux derniers de chaque poule
            $participations = RencontreUtils::participationsDeuxderniersPoule($poules, $participationsPoule, $event1);
        }

        foreach ($participations as $participation) {
            $em->persist($participation);
        }

        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ['id' => $event1->getId()]);
    }

    /**
     * fonction qui permet de créer event de 5et7et9et11emeplace
     * @Route("/creation5et7et9et11emePlace/{idEvent}", name="creation_5et7et9et11eme_place", requirements={"idEvent": "\d+"})
     */
    public
    function creer5et7et9et11emePlace($idEvent, EntityManagerInterface $em, Request $request)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $roundName = $request->request->get('round');
        $round = $em->getRepository(Round::class)->findOneBy(["name" => $roundName]);
        $matchs = $em->getRepository(Match::class)->findMatchesWithAnEvent($event);


        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn());
        $em->persist($event1);
        $participations = [];
        if ($roundName == "5ème place") {
            array_splice($matchs, 0, 2);
            $participations[] = RencontreUtils::recupParticipationsAvecWinnersde2Matchs($matchs, $event1);
        } elseif ($roundName == "7ème place") {
            array_splice($matchs, 0, 2);
            $participations[] = RencontreUtils::recupParticipationsAvecLoosersde2Matchs($matchs, $event1);
        } elseif ($roundName == "9ème place") {
            array_splice($matchs, 0, 4);
            $participations[] = RencontreUtils::recupParticipationsAvecWinnersde2Matchs($matchs, $event1);
        } elseif ($roundName == "11ème place") {
            array_splice($matchs, 0, 4);
            $participations[] = RencontreUtils::recupParticipationsAvecLoosersde2Matchs($matchs, $event1);
        }

        foreach ($participations as $participation) {
            $em->persist($participation);
        }

        $em->flush();
        return $this->redirectToRoute('admin_edit_event', ["id" => $event1->getId()]);
    }

    /**
     * fonction qui permet de créer barrages des poules
     * @Route("/creationBarrage/{idEvent}", name="creation_barrage", requirements={"idEvent": "\d+"})
     */
    public
    function creerBarrage($idEvent, EntityManagerInterface $em, ParticipationRepository $pr, MatchRepository $mr)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $round = $em->getRepository(Round::class)->findOneBy(["name" => "Barrage"]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn() + 1);
        $em->persist($event1);

        $poules = $pr->nbrPoules($idEvent);

        for ($i = 0; $i < sizeof($poules); $i++) {
            $participationsPoule[] = $pr->getParPoules($idEvent, $poules[$i]->getPoule());
        }

        //Etabli le classement par nbr de points
        $participationsPoule = (new \App\Utils\EventUtils($mr))->classerParPointsPoules($participationsPoule, $poules);

        $participations = RencontreUtils::recupererEquipePourCreationBarrage($participationsPoule);

        for ($i = 0; $i < sizeof($participations); $i++) {
            $participation = new Participation();
            $participation->setEvent($event1);
            $participation->setParticipant($participations[$i]->getParticipant());
            $em->persist($participation);
        }

        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ['id' => $event1->getId()]);
    }

    /**
     * fonction qui permet de créer event de 5emeplace poule 9
     * @Route("/creation5emePlace9/{idEvent}", name="creation_5eme_place_9", requirements={"idEvent": "\d+"})
     */
    public
    function creer5emePlace9($idEvent, EntityManagerInterface $em, Request $request)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $matchs = $em->getRepository(Match::class)->findMatchesWithAnEvent($event);
        $roundName = $request->request->get('round');
        $round = $em->getRepository(Round::class)->findOneBy(["name" => $roundName]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn());
        $em->persist($event1);

        $participations = RencontreUtils::recupParticipationsAvecLoosersde2Matchs($matchs, $event1);
        foreach ($participations as $participation) {
            $em->persist($participation);
        }

        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ["id" => $event1->getId()]);
    }

    /**
     * fonction qui permet de créer demi finales post-barrages
     * @Route("/creationDemiFinalePostBarrage/{idEvent}", name="creation_demi_finale_barrage", requirements={"idEvent": "\d+"})
     */
    public
    function creerDemiFinaleBarrage($idEvent, EntityManagerInterface $em, ParticipationRepository $pr, MatchRepository $mr)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $matchs = $em->getRepository(Match::class)->findMatchesWithAnEvent($event);
        $roundDemi = $em->getRepository(Round::class)->findOneBy(["name" => "1/2 finale"]);
        $roundPoules = $em->getRepository(Round::class)->findOneBy(["name" => "Phase de poules 1"]);
        $matchsPoule = $em->getRepository(Match::class)->findMatchesWithAnEventAndRound($event->getName(), $roundPoules, $event->getCompetition());

        $event1 = EventUtils::creationPhase($event, $roundDemi);
        $event1->setPhaseIn($event->getPhaseIn() + 1);
        $em->persist($event1);

        $poules = $pr->nbrPoules($matchsPoule[0]->getEvent()->getId());

        for ($i = 0; $i < sizeof($poules); $i++) {
            $participationsPoule[] = $pr->getParPoules($matchsPoule[0]->getEvent()->getId(), $poules[$i]->getPoule());
        }
        //Etabli le classement par nbr de points
        $participationsPoule = (new \App\Utils\EventUtils($mr))->classerParPointsPoules($participationsPoule, $poules);

        for ($j = 0; $j < sizeof($participationsPoule); $j++) {
            for ($k = 0; $k < 1; $k++) {
                $participation = new Participation();
                $participation->setEvent($event1);
                $participation->setParticipant($participationsPoule[$j][$k]->getParticipant());
                $em->persist($participation);
            }
        }
        $participations = RencontreUtils::recupParticipationsAvecWinnersde2Matchs($matchs, $event1);
        foreach ($participations as $participation) {
            $em->persist($participation);
        }

        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ["id" => $event1->getId()]);
    }

    /**
     * fonction qui permet de créer une poule de classement 9
     * @Route("/creationPouleClassement9/{idEvent}", name="creation_poule_classement_9", requirements={"idEvent": "\d+"})
     */
    public
    function creerPouleClassement9($idEvent, EntityManagerInterface $em, ParticipationRepository $pr, MatchRepository $mr)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $round = $em->getRepository(Round::class)->findOneBy(["name" => "Poule de classement"]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn() + 1);
        $em->persist($event1);

        $poules = $pr->nbrPoules($idEvent);

        for ($i = 0; $i < sizeof($poules); $i++) {
            $participationsPoule[] = $pr->getParPoules($idEvent, $poules[$i]->getPoule());
        }

        //Etabli le classement par nbr de points
        $participationsPoule = (new \App\Utils\EventUtils($mr))->classerParPointsPoules($participationsPoule, $poules);

        $m = 0;
        $k = 1;
        if ((sizeof($participationsPoule[$m]) + sizeof($participationsPoule[$m + 1])) % 2 == 1) {

            if (sizeof($participationsPoule[$m]) > sizeof($participationsPoule[$m + 1])) {

                for ($j = 0; $j < 2; $j++) {
                    $participation = new Participation();
                    $participation->setEvent($event1);
                    $participation->setParticipant($participationsPoule[$m][sizeof($participationsPoule[$m]) - $k]->getParticipant());
                    $em->persist($participation);
                    $k++;
                }
                $participation = new Participation();
                $participation->setEvent($event1);
                $participation->setParticipant($participationsPoule[$m + 1][sizeof($participationsPoule[$m + 1]) - 1]->getParticipant());
                $em->persist($participation);
            } elseif (sizeof($participationsPoule[$m]) < sizeof($participationsPoule[$m + 1])) {
                for ($j = 0; $j < 2; $j++) {
                    $participation = new Participation();
                    $participation->setEvent($event1);
                    $participation->setParticipant($participationsPoule[$m + 1][sizeof($participationsPoule[$m + 1]) - $k]->getParticipant());
                    $em->persist($participation);
                    $k++;
                }
                $participation = new Participation();
                $participation->setEvent($event1);
                $participation->setParticipant($participationsPoule[$m][sizeof($participationsPoule[$m]) - 1]->getParticipant());
                $em->persist($participation);
            }

        }

        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ["id" => $event1->getId()]);
    }

    /**
     * Fonction qui permet de créer tournoi phase2
     * @Route("/creerTournoiPhase2/{idEvent}", name="creer_tournoi_phase2", requirements={"idEvent": "\d+"})
     */
    public function creerConsolante($idEvent, EntityManagerInterface $em, MatchRepository $mr, ParticipationRepository $pr, Request $request)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $roundName = $request->request->get('round');
        $round = $em->getRepository(Round::class)->findOneBy(["name" => $roundName]);
        $poules = $pr->nbrPoules($idEvent);

        for ($i = 0; $i < sizeof($poules); $i++) {
            $participationsPoule[] = $pr->getParPoules($idEvent, $poules[$i]->getPoule());
        }

        //Etabli le classement par nbr de points
        $participationsPoule = (new \App\Utils\EventUtils($mr))->classerParPointsPoules($participationsPoule, $poules);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPoule(false);
        $event1->setPhaseIn($event->getPhaseIn() + 1);
        $em->persist($event1);

        if ($roundName == "Tournoi consolante") {
            $event1->setPoule(true);
            $participations = RencontreUtils::participationsDeuxderniersPoule($poules, $participationsPoule);
        } else {
            $participations = RencontreUtils::creerTournoiPrincipal($poules, $participationsPoule);
        }

        foreach ($participations as $particip) {
            $participation = new Participation();
            $participation->setEvent($event1);
            $participation->setParticipant($particip->getParticipant());
            $em->persist($participation);
        }
        $em->flush();
        return $this->redirectToRoute('admin_edit_event', ['id' => $event1->getId()]);
    }

    /**
     * fonction qui créé les places honneur du tournoi consolante
     * @Route("/creerPlaceHonneurConsolante/{idEvent}", name="creer_place_honneur_consolante", requirements={"idEvent": "\d+"})
     */
    public function creerPlaceHonneurConsolante($idEvent, MatchRepository $mr, EntityManagerInterface $em, ParticipationRepository $pr, Request $request)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $roundName = $request->request->get('round');
        $round = $em->getRepository(Round::class)->findOneBy(["name" => $roundName]);
        $participationsDebut = $pr->findParticipationInAnEventSimple($idEvent);


        $event1 = EventUtils::creationPhase($event, $round);
        if ($event->getPhaseIn() == 1) {
            $event1->setPhaseIn(2);
        }
        $event1->setPoule(false);
        $em->persist($event1);

        $poules = $pr->nbrPoules($idEvent);

        for ($i = 0; $i < sizeof($poules); $i++) {
            $participationsPoule[] = $pr->getParPoules($idEvent, $poules[$i]->getPoule());
        }
        $participations = [];

        //Etabli le classement par nbr de points
        $participationsPoule = (new \App\Utils\EventUtils($mr))->classerParPointsPoules($participationsPoule, $poules);

        if (sizeof($participationsDebut) == 18) {
            $participations = RencontreUtils::participations17emeplace($participationsPoule);
        } else {
            $k = 0;
            if ($roundName === "11ème place") {
                $k = 1;
            } elseif ($roundName === "13ème place") {
                $k = 2;
            }

            for ($j = 0; $j < 2; $j++) {
                for ($i = $k; $i < $k++; $i++) {
                    $participations[] = $participationsPoule[$j][$i];
                }
            }
        }

        for ($i = 0; $i < sizeof($participations); $i++) {
            $participation = new Participation();
            $participation->setEvent($event1);
            $participation->setParticipant($participations[$i]->getParticipant());
            $em->persist($participation);
        }

        $em->flush();
        return $this->redirectToRoute('admin_edit_event', ["id" => $event1->getId()]);
    }

    /**
     * Fonction qui créé une poule de classement pour places 13-14-15
     * @Route("/creerPouleClassement15Equipes/{idEvent}", name="poule_classement_15", requirements={"idEvent": "\d+"})
     */
    public function pouleClassement15Equipes($idEvent, EntityManagerInterface $em, Request $request)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $roundName = $request->request->get('round');
        $round = $em->getRepository(Round::class)->findOneBy(["name" => $roundName]);
        $matchs = $em->getRepository(Match::class)->findMatchesWithAnEvent($event);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPoule(false);
        $em->persist($event1);

        $participations = RencontreUtils::recupParticipationsAvecLoosers($matchs, $event1);

        foreach ($participations as $participation) {
            $em->persist($participation);
        }

        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ['id' => $event1->getId()]);
    }

    /**
     * Fonction qui créé une poule de classement pour places 17-18-19
     * @Route("/creerPouleClassement19Equipes/{idEvent}", name="poule_classement_19", requirements={"idEvent": "\d+"})
     */
    public function pouleClassement19Equipes($idEvent, MatchRepository $mr, EntityManagerInterface $em, Request $request, ParticipationRepository $pr)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $roundName = $request->request->get('round');
        $round = $em->getRepository(Round::class)->findOneBy(["name" => $roundName]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn() + 1);
        $event1->setPoule(false);
        $em->persist($event1);

        $poules = $pr->nbrPoules($idEvent);

        for ($i = 0; $i < sizeof($poules); $i++) {
            $participationsPoule[] = $pr->getParPoules($idEvent, $poules[$i]->getPoule());
        }
        $participations = [];

        //Etabli le classement par nbr de points
        $participationsPoule = (new \App\Utils\EventUtils($mr))->classerParPointsPoules($participationsPoule, $poules);

        //Récupérer les 5ème de poule
        for ($i = 0; $i < sizeof($participationsPoule); $i++) {
            if (sizeof($participationsPoule[$i]) == 5) {
                $participations[] = $participationsPoule[$i][sizeof($participationsPoule[$i]) - 1];
            }
        }

        foreach ($participations as $particip) {
            $participation = new Participation();
            $participation->setEvent($event1);
            $participation->setParticipant($particip->getParticipant());
            $em->persist($participation);
        }

        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ['id' => $event1->getId()]);
    }

    /**
     * fonction qui permet de créer les quarts de finale a partir 15equipes
     * @Route("/creationquartFinaleAPartir15Equipes/{idEvent}", name="creation_quart_a_partir_15", requirements={"idEvent": "\d+"})
     */
    public
    function creerQuartAPartir15equipes($idEvent, MatchRepository $mr, EntityManagerInterface $em, Request $request)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $roundName = $request->request->get('round');
        $participationsDebut = $em->getRepository(Participation::class)->findParticipationInAnEventSimple($idEvent);
        $round = $em->getRepository(Round::class)->findOneBy(["name" => $roundName]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn() + 1);
        if ($event->getPhase() == 3) {
            $event1->setPoule(true);
        }
        $em->persist($event1);

        $poules = $em->getRepository(Participation::class)->nbrPoules($idEvent);
        //Récupération des participations par poules de la phase1
        for ($i = 0; $i < sizeof($poules); $i++) {
            $participationsPoule[] = $em->getRepository(Participation::class)->getParPoules($idEvent, $poules[$i]->getPoule());
        }
        $participationsPoule = (new \App\Utils\EventUtils($mr))->classerParPointsPoules($participationsPoule, $poules);
        $participations = RencontreUtils::participations2premiersPoule($participationsPoule, $event1);

        if (sizeof($participationsDebut) == 15 || sizeof($participationsDebut) == 18) {
            //Récupérer les 2 meilleurs troisièmes
            //Récupérer tous le 3ème de chaque poule
            $les3emes = [];
            for ($i = 0; $i < sizeof($participationsPoule); $i++) {
                $les3emes[] = $participationsPoule[$i][2];
            }
            //Classer les 3emes par points
            $les3emes = (new \App\Utils\EventUtils($mr))->classerParPoints($les3emes);

            if ($les3emes[0]->getPoule() === 'A' && $les3emes[1]->getPoule() === 'B') {
                for ($i = 0; $i < 2; $i++) {
                    $participation = new Participation();
                    $participation->setEvent($event1);
                    $participation->setParticipant($les3emes[$i]->getParticipant());
                    array_push($participations, $participation);
                }
            } elseif ($les3emes[0]->getPoule() === 'A' && $les3emes[1]->getPoule() === 'C') {
                for ($i = 0; $i < 2; $i++) {
                    $participation = new Participation();
                    $participation->setEvent($event1);
                    $participation->setParticipant($les3emes[$i]->getParticipant());
                    array_push($participations, $participation);
                }
            } elseif ($les3emes[0]->getPoule() === 'B' && $les3emes[1]->getPoule() === 'A') {
                for ($i = 1; $i = 0; $i--) {
                    $participation = new Participation();
                    $participation->setEvent($event1);
                    $participation->setParticipant($les3emes[$i]->getParticipant());
                    array_push($participations, $participation);
                }
            } elseif ($les3emes[0]->getPoule() === 'B' && $les3emes[1]->getPoule() === 'C') {
                for ($i = 1; $i = 0; $i--) {
                    $participation = new Participation();
                    $participation->setEvent($event1);
                    $participation->setParticipant($les3emes[$i]->getParticipant());
                    array_push($participations, $participation);
                }
            } elseif ($les3emes[0]->getPoule() === 'C' && $les3emes[1]->getPoule() === 'A') {
                for ($i = 1; $i = 0; $i--) {
                    $participation = new Participation();
                    $participation->setEvent($event1);
                    $participation->setParticipant($les3emes[$i]->getParticipant());
                    array_push($participations, $participation);
                }
            } elseif ($les3emes[0]->getPoule() === 'C' && $les3emes[1]->getPoule() === 'B') {
                for ($i = 0; $i < 2; $i++) {
                    $participation = new Participation();
                    $participation->setEvent($event1);
                    $participation->setParticipant($les3emes[$i]->getParticipant());
                    array_push($participations, $participation);
                }
            }
        }

        foreach ($participations as $participation) {
            $em->persist($participation);
        }
        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ["id" => $event1->getId()]);
    }

    /**
     * fonction qui permet de créer les huitièmes de finale a partir 16equipes
     * @Route("/creationHuitiemeFinaleAPartir16Equipes/{idEvent}", name="creation_huitième_a_partir_16", requirements={"idEvent": "\d+"})
     */
    public
    function creerHuitiemeAPartir16equipes($idEvent, MatchRepository $mr, EntityManagerInterface $em, Request $request)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $roundName = $request->request->get('round');
        $round = $em->getRepository(Round::class)->findOneBy(["name" => $roundName]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn() + 1);
        $em->persist($event1);

        $poules = $em->getRepository(Participation::class)->nbrPoules($idEvent);
        //Récupération des participations par poules de la phase1
        for ($i = 0; $i < sizeof($poules); $i++) {
            $participationsPoule[] = $em->getRepository(Participation::class)->getParPoules($idEvent, $poules[$i]->getPoule());
        }
        $participationsPoule = (new \App\Utils\EventUtils($mr))->classerParPointsPoules($participationsPoule, $poules);
        $participations = RencontreUtils::creerParticipationsHuitemeFinale16Equipes($participationsPoule, $event1);

        foreach ($participations as $participation) {
            $em->persist($participation);
        }
        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ["id" => $event1->getId()]);
    }


    /**
     * Fonction qui créé tournoi consolante 3 phases
     * @Route("/creerTournoiConsolante3Phases/{idEvent}", name="tournoi_consolante_3phases", requirements={"idEvent": "\d+"})
     */
    public function tournoiConsolante3phases($idEvent, EntityManagerInterface $em, MatchRepository $mr, Request $request, ParticipationRepository $pr)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $roundName = $request->request->get('round');
        $round = $em->getRepository(Round::class)->findOneBy(["name" => $roundName]);
        $participations = $pr->findParticipationInAnEventSimple($idEvent);

        $event1 = EventUtils::creationPhase($event, $round);
        if ($roundName == "Barrage") {
            $event1->setPhaseIn($event->getPhaseIn());
            $event1->setPoule(false);
            $event1->setPoule(false);
        } elseif ($roundName == "1/4 finale consolante") {
            $event1->setPhaseIn($event->getPhaseIn() + 1);
        } else {
            $event1->setPhaseIn($event->getPhaseIn() + 1);
            $event1->setPoule(true);
        }
        $em->persist($event1);

        $poules = $pr->nbrPoules($idEvent);

        for ($i = 0; $i < sizeof($poules); $i++) {
            $participationsPoule[] = $pr->getParPoules($idEvent, $poules[$i]->getPoule());
        }


        //Etabli le classement par nbr de points
        $participationsPoule = (new \App\Utils\EventUtils($mr))->classerParPointsPoules($participationsPoule, $poules);

        if ($roundName == "Barrage") {
            //Récupérer les 3emes des poules de 4
            for ($i = 1; $i < sizeof($participationsPoule); $i++) {
                $participation = new Participation();
                $participation->setEvent($event1);
                $participation->setParticipant($participationsPoule[$i][2]->getParticipant());
                $participations[] = $participation;
            }
        } elseif ($roundName == "1/4 finale consolante" || sizeof($participations) == 16 || sizeof($participations) == 14 || sizeof($participations) == 20) {
            //Récupérer les 2 derniers de chaque poule
            $participations = RencontreUtils::participationsDeuxderniersPoule($poules, $participationsPoule, $event1);
        } elseif (sizeof($participations) == 7) {
            //Récupérer les 3ème et 4eme de poule de phase 1
            $participations = RencontreUtils::participations3emeet4emePoule7($participationsPoule, $event1);
        } elseif (sizeof($participations) == 8 || sizeof($participations) == 12) {
            $participations = RencontreUtils::participations3emeet4eme($participationsPoule, $event1);
        } elseif (sizeof($participations) == 9) {
            $participations = RencontreUtils::participations4emeet5emePoule9($participationsPoule, $event1);
        } elseif (sizeof($participations) == 13) {
            //Récupération des 4et5emes
            $participations = RencontreUtils::participations4emeet5emePoule9($participationsPoule, $event1);
            //Récupération du perdant du barrage
            $roundBarrage = $em->getRepository(Round::class)->findOneBy(['name' => "Barrage"]);
            $matchBarrage = $em->getRepository(Match::class)->findMatchesWithAnEventAndRound($event->getName(), $roundBarrage, $event->getCompetition());
            $participation = new Participation();
            $participation->setEvent($event1);
            $participation->setParticipant($matchBarrage[0]->getLooser()->getParticipant());
            //Ajouter le perdant du barrage aux participations
            array_push($participations, $participation);
        } elseif (sizeof($participations) == 17) {
            $participations = RencontreUtils::participationsTournoiConsolante17($participationsPoule, $event1);
        } elseif (sizeof($participations) == 18) {
            //Récupération des 4eme et 5eme des poules
            $participations = RencontreUtils::participations4emeet5emePoule18($participationsPoule, $event1);
        } elseif (sizeof($participations) == 19) {
            //Récupération des 4eme et 5eme des poules
            $participations = RencontreUtils::participations4emeet5emePoule19($participationsPoule, $event1);
        } elseif (sizeof($participations) == 21) {
            //Récupération des 4eme et 5eme des poules
            $participations = RencontreUtils::participationsTournoiConsolante21($participationsPoule, $event1);
        }

        foreach ($participations as $participation) {
            $em->persist($participation);
        }

        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ['id' => $event1->getId()]);
    }

    /**
     * Fonction qui créé tournoi principal 3 phases
     * @Route("/creerTournoiPrincipal3Phases/{idEvent}", name="tournoi_principal_3phases", requirements={"idEvent": "\d+"})
     */
    public function tournoiPrincipal3phases($idEvent, MatchRepository $mr, EntityManagerInterface $em, Request $request, ParticipationRepository $pr)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $roundName = $request->request->get('round');
        $round = $em->getRepository(Round::class)->findOneBy(["name" => $roundName]);
        $participations = $pr->findParticipationInAnEventSimple($idEvent);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn() + 1);
        $event1->setPoule(true);
        $em->persist($event1);

        $poules = $pr->nbrPoules($idEvent);

        for ($i = 0; $i < sizeof($poules); $i++) {
            $participationsPoule[] = $pr->getParPoules($idEvent, $poules[$i]->getPoule());
        }

        //Etabli le classement par nbr de points
        $participationsPoule = (new \App\Utils\EventUtils($mr))->classerParPointsPoules($participationsPoule, $poules);

        if (sizeof($participations) == 7 || sizeof($participations) == 8 || sizeof($participations) == 12 || sizeof($participations) == 16 || sizeof($participations) == 17) {
            //Récupérer les 1er et 2eme de poule de phase 1
            $participations = RencontreUtils::participations2premiersPoule($participationsPoule, $event1);
        } elseif (sizeof($participations) == 9 || sizeof($participations) == 18 || sizeof($participations) == 19 || sizeof($participations) == 20) {
            $participations = RencontreUtils::participations3premiersPoule($participationsPoule, $event1);
        } elseif (sizeof($participations) == 13) {
            //Récupération des trois premiers de la poule de 5 puis 2 premiers des poules suivantes
            $participations = RencontreUtils::participationsTournoiprincipal3et2premiers($participationsPoule, $event1);
            //Récupération du gagnant du barrage
            $roundBarrage = $em->getRepository(Round::class)->findOneBy(['name' => "Barrage"]);
            $matchBarrage = $em->getRepository(Match::class)->findMatchesWithAnEventAndRound($event->getName(), $roundBarrage, $event->getCompetition());
            $participation = new Participation();
            $participation->setEvent($event1);
            $participation->setParticipant($matchBarrage[0]->getWinner()->getParticipant());
            //Ajouter le gagnant du barrage aux participations
            array_push($participations, $participation);
        } elseif (sizeof($participations) == 14) {
            $participations = RencontreUtils::participationsTournoiPrincipal14($participationsPoule, $event1);
        } elseif (sizeof($participations) == 21) {
            //Récupérer les 2 premiers de chaque poule
            $participations = RencontreUtils::participations2premiersPoule($participationsPoule, $event1);
            //Récupérer les 2 meilleurs troisieme
            //$troisiemes = RencontreUtils::participations3emeChaquePoule($participationsPoule, $event1);
            $troisieme = null;
            $dernier = null;
            for ($i = 0; $i < sizeof($participationsPoule); $i++) {
                for ($j = 2; $j < 3; $j++) {
                    //Récupère les 2 meilleurs troisieme en enlevant la rencontre  contre le dernier si poule de 5
                    if (sizeof($participationsPoule[$i]) > sizeof($participationsPoule[1])) {
                        $troisieme = $participationsPoule[$i][$j];
                        $dernier = $participationsPoule[0][sizeof($participationsPoule[0]) - 1];
                        $match = $mr->findMatchWithAnEventand2Participations($event, $troisieme, $dernier);
                        if(!isset($match[0])) {
                            $match = $mr->findMatchWithAnEventand2Participations($event, $dernier, $troisieme);
                        }
                        if ($match[0]->getParticipation1() == $troisieme) {
                            $troisieme->setPointsMarques($troisieme->getPointsMarques() - $match[0]->getScoreTeam1());
                            $troisieme->setPointsEncaisses($troisieme->getPointsEncaisses() - $match[0]->getScoreTeam2());
                        } else {
                            $troisieme->setPointsMarques($troisieme->getPointsMarques() - $match[0]->getScoreTeam2());
                            $troisieme->setPointsEncaisses($troisieme->getPointsEncaisses() - $match[0]->getScoreTeam1());
                        }
                        if ($match[0]->getWinner() == $troisieme) {
                            $troisieme->setPointsClassement($troisieme->getPointsClassement() - 3);
                            $troisieme->setVictory($troisieme->getVictory() - 1);
                        } elseif ($match[0]->getLooser() == $troisieme) {
                            $troisieme->setDefeat($troisieme->getDefeat() - 1);
                        } else {
                            $troisieme->setPointsClassement($troisieme->getPointsClassement() - 1);
                            $troisieme->setNul($troisieme->getNul() - 1);
                        }
                        $troisiemes[] = $troisieme;
                    } else {
                        $troisiemes[] = $participationsPoule[$i][$j];
                    }
                }
            }
            $troisiemes = EventUtils::classerParPointsClassique($troisiemes);
            for ($i = 0; $i < 2; $i++) {
                $participations[] = $troisiemes[$i];
            }
        }
        $match = $mr->findMatchWithAnEventand2Participations($event, $troisieme, $dernier);
        if(!isset($match[0])) {
            $match = $mr->findMatchWithAnEventand2Participations($event, $dernier, $troisieme);
        }
        if ($match[0]->getParticipation1() == $troisieme) {
            $troisieme->setPointsMarques($troisieme->getPointsMarques() + $match[0]->getScoreTeam1());
            $troisieme->setPointsEncaisses($troisieme->getPointsEncaisses() + $match[0]->getScoreTeam2());
        } else {
            $troisieme->setPointsMarques($troisieme->getPointsMarques() + $match[0]->getScoreTeam2());
            $troisieme->setPointsEncaisses($troisieme->getPointsEncaisses() + $match[0]->getScoreTeam1());
        }
        if ($match[0]->getWinner() == $troisieme) {
            $troisieme->setPointsClassement($troisieme->getPointsClassement() + 3);
            $troisieme->setVictory($troisieme->getVictory() + 1);
        } elseif ($match[0]->getLooser() == $troisieme) {
            $troisieme->setDefeat($troisieme->getDefeat() + 1);
        } else {
            $troisieme->setPointsClassement($troisieme->getPointsClassement() + 1);
            $troisieme->setNul($troisieme->getNul() + 1);
        }

        foreach ($participations as $participation) {
            $parti = new Participation();
            $parti->setEvent($event1);
            $parti->setParticipant($participation->getParticipant());
            $em->persist($parti);
        }

        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ['id' => $event1->getId()]);
    }

    /**
     * Fonction qui créé places 3-5-7-finale phase3
     * @Route("/creerPlacesFinalePhase3/{idEvent}", name="places_finale_phase3", requirements={"idEvent": "\d+"})
     */
    public function placesfinalePhase3($idEvent, MatchRepository $mr, EntityManagerInterface $em, Request $request, ParticipationRepository $pr)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $roundName = $request->request->get('round');
        $round = $em->getRepository(Round::class)->findOneBy(["name" => $roundName]);

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn() + 1);
        $event1->setPoule(false);
        $em->persist($event1);

        $poules = $pr->nbrPoules($idEvent);

        for ($i = 0; $i < sizeof($poules); $i++) {
            $participationsPoule[] = $pr->getParPoules($idEvent, $poules[$i]->getPoule());
        }
        $participations = [];

        //Etabli le classement par nbr de points
        $participationsPoule = (new \App\Utils\EventUtils($mr))->classerParPointsPoules($participationsPoule, $poules);

        if ($roundName == "Finale") {
            //Récupérer le 1er et 2eme de poule
            $participations = RencontreUtils::participations2premiersPoule($participationsPoule, $event1);
        } elseif ($roundName == "3ème place") {
            //Récupérer les deux derniers de la poule
            $participations = RencontreUtils::participationsDeuxderniersPoule($poules, $participationsPoule, $event1);
        } elseif ($roundName == "5ème place") {
            //Récupérer les deux derniers de la poule
            $participations = RencontreUtils::participations2premiersPoule($participationsPoule, $event1);
        } elseif ($roundName == "7ème place") {
            //Récupérer les deux derniers de la poule
            $participations = RencontreUtils::participationsDeuxderniersPoule($poules, $participationsPoule, $event1);
        } elseif ($roundName == "11ème place" || $roundName == "15ème place") {
            //Récupérer les deuxièmes des poules
            $participations = RencontreUtils::participationsDeuxiemesChaquePoule($participationsPoule, $event1);
        } elseif ($roundName == "13ème place") {
            //Récupérer les 2 moins bons deuxièmes des poules
            $participations = RencontreUtils::participations2moinsBonsdesDeuxiemes($participationsPoule, $event1);
        } elseif ($roundName == "17ème place") {
            //Récupérer les 3eme de chaque poule
            $participations = RencontreUtils::participations3emeChaquePoule($participationsPoule, $event1);
        } elseif ($roundName == "19ème place") {
            //Récupérer les 4emes de chaque poule
            $participations = RencontreUtils::participations4emeChaquePoule($participationsPoule, $event1);
            //Classer le dernier de la poule de 5
            $dernier = $participationsPoule[0][sizeof($participationsPoule[0]) - 1];
            $dernier->setPositionClassement(21);
        } elseif ($roundName = "1/2 finale consolante") {
            //Récupérer les 1er des poules
            $participations = RencontreUtils::participationsPremiersChaquePoule($participationsPoule, $event1);
            //Récupérer le meilleur deuxieme
            for ($i = 0; $i < sizeof($participationsPoule); $i++) {
                for ($j = 1; $j < 2; $j++) {
                    $deuxiemes[] = $participationsPoule[$i][$j];
                }
            }
            //Classer les deuxiemes par ordre de points
            $deuxiemes = (new \App\Utils\EventUtils($mr))->classerParPoints($deuxiemes);

            $participation = new Participation();
            $participation->setEvent($event1);
            $participation->setParticipant($deuxiemes[0]->getParticipant());
            array_push($participations, $participation);
        }


        foreach ($participations as $participation) {
            $em->persist($participation);
        }

        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ['id' => $event1->getId()]);
    }

    /**
     * Fonction qui créé places honneur phase3
     * @Route("/creerPlaceHonneurPhase3/{idEvent}", name="places_honneur_phase3", requirements={"idEvent": "\d+"})
     */
    public function placesHonneurPhase3($idEvent, MatchRepository $mr, EntityManagerInterface $em, Request $request, ParticipationRepository $pr)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $roundName = $request->request->get('round');
        $round = $em->getRepository(Round::class)->findOneBy(["name" => $roundName]);
        $roundPhase1 = $em->getRepository(Round::class)->findOneBy(['name' => 'Phase de poules 1']);
        $participationsDebut = $pr->findParticipationsWithAnEventAndRound($event->getName(), $roundPhase1, $event->getCompetition());

        $event1 = EventUtils::creationPhase($event, $round);
        $event1->setPhaseIn($event->getPhaseIn() + 1);
        $event1->setPoule(false);
        $em->persist($event1);

        $poules = $pr->nbrPoules($idEvent);

        for ($i = 0; $i < sizeof($poules); $i++) {
            $participationsPoule[] = $pr->getParPoules($idEvent, $poules[$i]->getPoule());
        }
        $participations = [];

        //Etabli le classement par nbr de points
        $participationsPoule = (new \App\Utils\EventUtils($mr))->classerParPointsPoules($participationsPoule, $poules);

        if (($roundName == "1/2 finale" || $roundName == "1/2 finale consolante") && (sizeof($participationsDebut) == 15 || sizeof($participationsDebut) == 16 || sizeof($participationsDebut) == 17)) {
            //Récupérer le 1er et 2eme de poule
            $participations1 = RencontreUtils::participations2premiersPoule($participationsPoule, $event1);
            //Récupérer le 3eme et 4eme de poule
            $participations2 = RencontreUtils::participations3emeet4eme($participationsPoule, $event1);
            $participations[] = $participations1;
            $participations[] = $participations2;

            foreach ($participations as $participation) {
                for ($i = 0; $i < sizeof($participation); $i++) {
                    $em->persist($participation[$i]);
                }
                $em->flush();
            }
            return $this->redirectToRoute('admin_edit_event', ['id' => $event1->getId()]);
        } elseif ($roundName == "1/2 finale" && (sizeof($participationsDebut) >= 18)) {
            $participations[] = RencontreUtils::participationsPremiersChaquePoule($participationsPoule, $event1);
            array_push($participations, RencontreUtils::participationsDeuxiemesChaquePoule($participationsPoule, $event1));
            array_push($participations, RencontreUtils::participations3emeChaquePoule($participationsPoule, $event1));
            for ($i = 0; $i < sizeof($participations); $i++) {
                for ($j = 0; $j < sizeof($participations[$i]); $j++) {
                    $em->persist($participations[$i][$j]);
                }
            }
        } elseif ($roundName == "1/2 finale") {
            //Récupérer le 1er et 2eme de poule
            $participations = RencontreUtils::participations2premiersPoule($participationsPoule, $event1);
        } elseif ($roundName == "1/2 finale consolante") {
            //Récupérer les deux premiers de la poule
            $participations = RencontreUtils::participations2premiersPoule($participationsPoule, $event1);
        } elseif ($roundName == "13ème place" && (sizeof($participationsDebut) >= 18)) {
            //Récupérer les premiers de chaque poule
            $participations = RencontreUtils::participationsPremiersChaquePoule($participationsPoule, $event1);
        } elseif ($roundName == "17ème place" && sizeof($participationsDebut) == 19) {
            //Classer le dernier de la poule de 4 au classement général
            $dernier = $participationsPoule[0][sizeof($participationsPoule[0]) - 1];
            $dernier->setPositionClassement(19);
            //Récupérer les troisièmes de chaque poule
            $participations = RencontreUtils::participations3emeChaquePoule($participationsPoule, $event1);
        } elseif ($roundName == "5ème place" || $roundName == "11ème place" || $roundName == "13ème place") {
            //Récupérer les 3 emes des poules
            $participations = RencontreUtils::participations3emeChaquePoule($participationsPoule, $event1);
        } elseif ($roundName == "7ème place" || $roundName == "Poule de classement" || $roundName == "17ème place" || $roundName == "19ème place") {
            //Récupérer les derniers de chaque poule
            $participations = RencontreUtils::participationsDerniersChaquePoule($participationsPoule, $event1);
        } elseif ($roundName == "9ème place") {
            //Récupérer les premiers de chaque poule
            $participations = RencontreUtils::participationsPremiersChaquePoule($participationsPoule, $event1);
        }

        if (!($roundName == "1/2 finale" && sizeof($participationsDebut) >= 18)) {
            foreach ($participations as $participation) {
                $em->persist($participation);
            }
        }
        $em->flush();

        return $this->redirectToRoute('admin_edit_event', ['id' => $event1->getId()]);
    }


    /**
     * @Route("/afficherMultiMatchs/{idMatch}", name="afficher_multiMatchs", requirements={"idMatch": "\d+"})
     */
    public function afficherMultiMatchs($idMatch, EntityManagerInterface $em)
    {
        $rencontres = $em->getRepository(Rencontre::class)->recupererRencontresAvecIdMatch($idMatch);
        if (empty($rencontres)) {
            $match = $em->getRepository(Match::class)->find($idMatch);
            $matchs = RencontreUtils::multiMatchs($match, $match->getEvent());
            foreach ($matchs as $match) {
                $em->persist($match);
            }
            $em->flush();
            $rencontres = $em->getRepository(Rencontre::class)->recupererRencontresAvecIdMatch($idMatch);
        }
        $rencontre = $rencontres[0];
        return $this->render('admin/rencontres.html.twig', [
            "rencontres" => $rencontres,
            "rencontre" => $rencontre
        ]);
    }

    /**
     * @Route("/genererResultats/{idEvent}", name="generer_resultats", requirements={"idEvent": "\d+"})
     */
    public function genererResultats($idEvent, EntityManagerInterface $em)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $matchs = $em->getRepository(Match::class)->findMatchesWithAnEvent($event);

        EventUtils::genererResultatsMatchsAleatoire($matchs);

        $em->flush();

        return $this->redirectToRoute('admin_see_planning_meets', ["idEvent" => $idEvent]);
    }

    /**
     * @Route("/attribuerMedailles/{idEvent}", name="attribuer_medailles", requirements={"idEvent": "\d+"})
     * Fonction qui attribue les medailles pour les event à 1 phase
     */
    public function attribuerMedailles($idEvent, EntityManagerInterface $em, MatchRepository $mr){
        $participations = $em->getRepository(Participation::class)->findParticipationInAnEventSimple($idEvent);
        $participations =  (new \App\Utils\EventUtils($mr))->classerParPoints($participations);

        $participations[0]->setGoldMedal($participations[0]->getGoldMedal() + 1);
        $participations[1]->setSilverMedal($participations[1]->getSilverMedal() + 1);
        $participations[2]->setBronzeMedal($participations[2]->getBronzeMedal() + 1);

        if($participations[0]->getEvent()->getType()->getName() === 'Tournoi individuel'){
            $participations[0]->getParticipant()->getAthlet()->setGoldMedal($participations[0]->getParticipant()->getAthlet()->getGoldMedal() + 1);
            $participations[0]->getParticipant()->getAthlet()->getCompany()->setGoldMedal($participations[0]->getParticipant()->getAthlet()->getCompany()->getGoldMedal() + 1);
            $participations[1]->getParticipant()->getAthlet()->setSilverMedal($participations[1]->getParticipant()->getAthlet()->getSilverMedal() + 1);
            $participations[1]->getParticipant()->getAthlet()->getCompany()->setSilverMedal($participations[1]->getParticipant()->getAthlet()->getCompany()->getSilverMedal() + 1);
            $participations[2]->getParticipant()->getAthlet()->setBronzeMedal($participations[2]->getParticipant()->getAthlet()->getBronzeMedal() + 1);
            $participations[2]->getParticipant()->getAthlet()->getCompany()->setBronzeMedal($participations[2]->getParticipant()->getAthlet()->getCompany()->getBronzeMedal() + 1);
        }else{
            $participations[0]->getParticipant()->getTeam()->getCompany()->setGoldMedal($participations[0]->getParticipant()->getTeam()->getCompany()->getGoldMedal() + 1);
            $participations[1]->getParticipant()->getTeam()->getCompany()->setSilverMedal($participations[1]->getParticipant()->getTeam()->getCompany()->getSilverMedal() + 1);
            $participations[2]->getParticipant()->getTeam()->getCompany()->setGoldMedal($participations[2]->getParticipant()->getTeam()->getCompany()->getGoldMedal() + 1);
        }
        $em->flush();

        $this->addFlash('success', "Médailles attribuées");
        return $this->redirectToRoute('event_afficher_classement', [
            "idEvent" => $idEvent
        ]);
    }

}
