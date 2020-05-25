<?php

namespace App\Tests\Project;

use App\Entity\Task;
use App\Entity\User;
use function foo\func;

use App\Entity\Project;
use DateTime;
use Liior\SymfonyTestHelpers\WebTestCase;

class ProjectRepositoryTest extends WebTestCase
{

    public function test_it_can_well_tasks_from_a_project()
    {



        $user = $this->createOne(User::class, function (User $user) {
            $user->setFullname('jojo')
                ->setEmail('robert@gmail.com')
                ->setPassword('osef');
        });

        $project = $this->createOne(Project::class, function (Project $project) use ($user) {

            $project->setTitle('test');
            $project->setDescription('teste');
            $project->setShortDescription('testeee');
            $project->setCreatedAt(new DateTime());
            $project->setOwner($user);
        });

        $this->createMany(Task::class, 3, function (TASK $task) use ($project) {

            $task->setTitle('titre');
            $task->setDescription('description');
            $task->setCreatedAt(new DateTime());
            $task->setCompleted(true);
            $task->setProject($project);
        });


        $this->createMany(Task::class, 3, function (TASK $task) use ($project) {

            $task->setTitle('titre');
            $task->setDescription('description');
            $task->setCreatedAt(new DateTime());
            $task->setCompleted(false);
            $task->setProject($project);
        });







        /** @var TaskRepository */
        $taskRepository = $this->getRepository(Task::class);

        $completed = $taskRepository->countByProject($project, true);
        $incomplete = $taskRepository->countByProject($project, false);
        $total = $taskRepository->countByProject($project);

        $this->assertEquals(3, $completed);
        $this->assertEquals(3, $incomplete);
        $this->assertEquals(6, $total);
    }
}
