<?php

/**
 * This file is part of the "Doctrine service layer" bundle.
 *
 * Copyright Dmitry Bykadorov <dmitry.bykadorov@gmail.com>
 *
 */

namespace Hudson\Bundle\DoctrineServiceLayerBundle\Model\Manager;

use Hudson\Bundle\DoctrineServiceLayerBundle\Model\Manager\ManagerInterface;
use Hudson\Bundle\DoctrineServiceLayerBundle\Model\Manager\Exception\ModelManagerException;
use Symfony\Component\DependencyInjection\Container;

/**
 * AbstractManager
 *
 * @author Dmitry Bykadorov <dmitry.bykadorov@gmail.com>
 */
abstract class AbstractManager implements ManagerInterface
{
    /**
     * @var string
     */
    protected $managedClass;

    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $entityRepository;

    /**
     * Constructor.
     *
     * @param string    $managedClass FQCN of the managed class
     * @param Container $container    DI container
     */
    public function __construct($managedClass, Container $container = null)
    {
        $this->initialize($managedClass, $container);
    }

    /**
     * Manager initialization.
     *
     * @param string    $managedClass FQCN of the managed class
     * @param Container $container    DI container
     */
    protected function initialize($managedClass, Container $container = null)
    {
        $this->setManagedClass($managedClass);
        $this->setContainer($container);

        /** @var $entityManager \Doctrine\ORM\EntityManager */
        $entityManager = $this->container->get('doctrine')->getManager();
        $this->entityManager = $entityManager;

        $this->entityRepository = $entityManager->getRepository($this->getManagedClass());
    }

    /**
     * Create instance of managed entity.
     *
     * @param null|array $data       Default values.
     * @param bool       $andPersist Persist object automatically or not.
     *
     * @return object
     */
    public function create($data = null, $andPersist = false)
    {
        $managedClass = $this->getManagedClass();

        return new $managedClass();
    }

    /**
     * Set DI container.
     *
     * @param \Symfony\Component\DependencyInjection\Container $container DI container
     */
    public function setContainer(Container $container = null)
    {
        $this->container = $container;
    }

    /**
     * Checks that concrete manager supports given object.
     *
     * @param object $object Object to check.
     *
     * @throws ModelManagerException
     *
     * @return bool
     */
    public function isSupported($object)
    {
        $managedClass = $this->getManagedClass();

        if (!is_object($object)) {
            throw new ModelManagerException(sprintf('Expected object, "%s" given', gettype($object)));
        } elseif (!$object instanceof $managedClass) {
            throw new ModelManagerException(
                sprintf('Model manager for "%s" does not supports class "%s"', $managedClass, get_class($object))
            );
        }

        return true;
    }

    /**
     * Returns FQCN of the managed class.
     *
     * @throws ModelManagerException
     *
     * @return string
     */
    public function getManagedClass()
    {
        return $this->managedClass;
    }

    /**
     * Set FQCN of the managed class.
     *
     * @param string $managedClass Fully qualified class name.
     */
    public function setManagedClass($managedClass)
    {
        $this->checkManagedClass($managedClass);

        $this->managedClass = $managedClass;
    }

    /**
     * Check managed class name.
     *
     * @param string $managedClass Managed class name.
     *
     * @throws ModelManagerException
     */
    protected function checkManagedClass($managedClass)
    {
        if (empty($managedClass)) {
            throw new ModelManagerException(sprintf('Empty managed class name'));
        } elseif (!is_string($managedClass)) {
            throw new ModelManagerException(
                sprintf(
                    'Managed class name must be a string with fully qulified class name, "%s" given',
                    gettype($managedClass)
                )
            );
        } elseif (!class_exists($managedClass)) {
            throw new ModelManagerException(
                sprintf(
                    'Managed class "%s" does not exists or can\'t be autoloaded',
                    $managedClass
                )
            );
        }
    }

    /**
     * Delete given object.
     *
     * @param object $object   Entity instance.
     * @param bool   $andFlush Flush object automatically or not.
     */
    public function delete($object, $andFlush = true)
    {
        $this->isSupported($object);

        $this->entityManager->remove($object);

        if ($andFlush) {
            $this->entityManager->flush();
        }
    }

    /**
     * Update given object.
     *
     * @param object $object     Entity instance.
     * @param bool   $andPersist Persist object automatically or not.
     * @param bool   $andFlush   Flush object automatically or not.
     *
     * @return void
     */
    public function update($object, $andPersist = true, $andFlush = true)
    {
        $this->isSupported($object);

        if ($andPersist) {
            $this->entityManager->persist($object);
            if ($andFlush) {
                $this->entityManager->flush();
            }
        }
    }

    /**
     * Return entity repository.
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->entityRepository;
    }

    /**
     * Proxy for repository findAll.
     * Returns all records in the table.
     *
     * @return array
     */
    public function findAll()
    {
        return $this->entityRepository->findAll();
    }

    /**
     * Proxy for repository find.
     * Finds an entity by its primary key / identifier.
     *
     * @param mixed   $id          The identifier.
     * @param int     $lockMode    Lock mode
     * @param integer $lockVersion Lock version
     *
     * @return object The entity.
     */
    public function find($id, $lockMode = \Doctrine\DBAL\LockMode::NONE, $lockVersion = null)
    {
        return $this->entityRepository->find($id, $lockMode, $lockVersion);
    }

    /**
     * Proxy for repository findBy.
     * Finds entities by a set of criteria.
     *
     * @param array      $criteria Criteria to find
     * @param array|null $orderBy  Ordering rules
     * @param int|null   $limit    Limit query
     * @param int|null   $offset   Offset query
     *
     * @return array The objects.
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->entityRepository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Proxy for repository findOneBy.
     * Finds a single entity by a set of criteria.
     *
     * @param array $criteria Criteria to find
     *
     * @return object
     */
    public function findOneBy(array $criteria)
    {
        return $this->entityRepository->findOneBy($criteria);
    }
}
