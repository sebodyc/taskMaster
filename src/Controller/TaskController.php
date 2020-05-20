<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{


    /**
     * 
     * @Route("/tasks/{id}/complete", name="task_complete")
     *
     * 
     */
    public function complete(Task $task, EntityManagerInterface $em)
    {

        $task->setCompleted(true);
        $em->flush();

        return $this->redirectToRoute('project_show', ['id' => $task->getProject()->getId()]);
    }

    /**
     * 
     * @Route("/tasks/{id}/reopen", name="task_reopen")
     *
     */
    public function reopen(Task $task, EntityManagerInterface $em)
    {
        if ($this->isGranted('CAN_MANAGE_TASK', $task->getProject()) === false) {
            $this->addFlash("danger", "vous ne pouvez pas faire ca");
            return $this->redirectToRoute("project_show", [
                'id' => $task->getProject()->getId()
            ]);
        }

        $task->setCompleted(false);
        $em->flush();

        return $this->redirectToRoute('project_show', ['id' => $task->getProject()->getId()]);
    }

    /**
     * 
     * @Route("/tasks/{id}/delete", name="task_delete")
     *
     */
    public function delete(Task $task, EntityManagerInterface $em)
    {
        $em->remove($task);
        $em->flush();

        return $this->redirectToRoute('project_show', ['id' => $task->getProject()->getId()]);
    }
}
