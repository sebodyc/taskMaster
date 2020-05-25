<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Project;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findWithTasks(User $user)
    {

        $query = $this->createQueryBuilder('p')
            ->select('p.title AS project , t.title, u.fullname ,t.completed')
            ->join('p.tasks', 't')
            ->join('p.owner', 'u')
            ->andWhere('p.owner = :user')
            ->setParameter('user', $user)
            ->getQuery();

        return $query->getResult();
    }

    public function countByProject(Project $project, bool $completed = null)
    {
        $builder = $this->createQueryBuilder('t')
            ->select('COUNT(t)')
            ->where('t.project= :project')
            ->setParameter('project', $project);

        if ($completed !== null) {
            $builder->andWhere('t.completed= :completed')
                ->setParameter('completed', $completed);
        }

        $query = $builder->getQuery();
        $query->getSingleScalarResult();

        return $query->getSingleScalarResult();
    }

    // /**
    //  * @return Project[] Returns an array of Project objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Project
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
