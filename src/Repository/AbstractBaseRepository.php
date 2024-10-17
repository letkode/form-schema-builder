<?php

namespace Letkode\FormSchemaBuilder\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryProxy;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractBaseRepository extends ServiceEntityRepositoryProxy
{
    public function __construct(
        ManagerRegistry $managerRegistry
    ) {
        parent::__construct($managerRegistry, $this->getEntityClassAssoc());
    }

    abstract protected function getEntityClassAssoc(): string;

    /**
     * > This function adds an entity to the database
     */
    public function add(object $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * > This function saves an entity to the database
     */
    public function save(object $entity): void
    {
        $this->add($entity, true);
    }

    /**
     * It removes an entity from the database
     */
    public function remove(object $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush($entity);
        }
    }

    /**
     * > This function removes an entity from the database
     */
    public function destroy(object $entity): void
    {
        $this->remove($entity, true);
    }

}