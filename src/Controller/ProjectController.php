<?php

namespace App\Controller;

use App\Entity\Project;
use cebe\markdown\Parser;
use cebe\markdown\Markdown;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Markdown\CachedMarkdownParser;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{
    /**
     * @Route("/projects", name="project_index")
     */
    public function index(ProjectRepository $projectRepository)
    {

        return $this->render('project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
        ]);
    }

    /**
     * @Route("/projects/{id}", name="project_show")
     */
    public function show(Project $project, CachedMarkdownParser $Parser)
    {
        

        $description = $Parser->parse($project->getDescription());


        return $this->render('project/show.html.twig', [
            "project" => $project,
            "description" => $description
        ]);
    }
}
