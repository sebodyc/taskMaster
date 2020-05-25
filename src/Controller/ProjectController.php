<?php

namespace App\Controller;

use DateTime;
use App\Form\TaskType;
use App\Entity\Project;
use App\Form\ProjectType;
use cebe\markdown\Parser;
use cebe\markdown\Markdown;
use App\Entity\Participation;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Service\Markdown\CachedMarkdownParser;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{
    /**
     * @Route("/", name="project_index")
     */
    public function index(ProjectRepository $projectRepository)
    {
        $user = $this->getUser();
        // dd($projectRepository->findWithTasks($user));


        return $this->render('project/index.html.twig', [
            'projects' => $projectRepository->findBy(['owner' => $user]),
        ]);
    }

    /**
     * @Route("/projects/{id<\d+>}", name="project_show")
     */
    public function show(Project $project, CachedMarkdownParser $Parser, request $request, EntityManagerInterface $em, MailerInterface $mailer, TaskRepository $taskRepository)
    {




        if ($this->isGranted('CAN_VIEW_PROJECT', $project) === false) {

            $this->addFlash('danger', " tu n'a pas le droit dy acceder tu peux quand meme demander l'acces");

            return $this->redirectToRoute('project_index');
        }

        $form = $this->createForm(TaskType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $task->setCompleted(false);
            $task->setCreatedAt(new DateTime());
            $task->setProject($project);

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', "la tache a bien ete cree");

            foreach ($project->getParticipations() as $participation) {

                $contributorMail = $participation->getUser()->getemail();
                $contributorName = $participation->getUser()->getfullname();

                $registerMessage = new MailerController;
                $registerMessage->sendEmailNewTask(
                    $mailer,
                    $contributorMail,
                    'Le manager a ajouter une nouvelle tache',
                    'emails/newTask.html.twig',
                    $contributorName,
                    $project->getTitle()
                );
            }

            return $this->redirectToRoute("project_show", [
                'id' => $project->getId()

            ]);
        }

        $total = $taskRepository->countByProject($project);
        $totalCompleted = $taskRepository->countByProject($project, true);
        $completionRate = 0;
        if ($total) {
            $completionRate = intval($totalCompleted / $total * 100);
        }



        return $this->render('project/show.html.twig', [
            "project" => $project,
            'taskForm' => $form->createView(),
            "completionRate" => $completionRate,
        ]);
    }


    /**
     * @Route("/projects/create", name="project_create")
     */
    public function create(request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm(ProjectType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $project = $form->getData();
            //plus besoin de le renseigner car projectEntityListener le fait
            // $project->setCreatedAt(new DateTime())
            //     ->setOwner($this->getUser());

            $em->persist($project);
            $em->flush();
            $this->addFlash('success', 'votre projet a ete crée');

            return $this->redirectToRoute('project_show', [
                'id' => $project->getId()
            ]);
        }

        return $this->render('project/create.html.twig', [
            'projectForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/Project/edit/{id}", name="project_edit")
     */
    public function edit(Project $project, Request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();
            return $this->redirectToRoute('project_show', [
                'id' => $project->getId()
            ]);
        }
        return $this->render('project/edit.html.twig', [
            'projectForm' => $form->createView()

        ]);
    }

    /**
     * 
     * @Route("/Projects/{id}/delete", name="project_delete")
     *
     */
    public function delete(Project $project, EntityManagerInterface $em)
    {
        $em->remove($project);
        $em->flush();

        return $this->redirectToRoute('project_index');
    }
}
