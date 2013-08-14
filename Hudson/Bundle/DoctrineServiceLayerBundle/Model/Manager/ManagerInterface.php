<?php

/**
 * This file is part of the "Doctrine service layer" bundle.
 *
 * Copyright Dmitry Bykadorov <dmitry.bykadorov@gmail.com>
 *
 */

namespace Hudson\Bundle\DoctrineServiceLayerBundle\Model\Manager;

use Hudson\Bundle\DoctrineServiceLayerBundle\Model\Manager\Exception\ModelManagerException;

/**
 * ManagerInterface
 *
 * @author Dmitry Bykadorov <dmitry.bykadorov@gmail.com>
 */
interface ManagerInterface
{
    /**
     * Create instance of managed entity.
     *
     * @param null|array $data       Default values.
     * @param bool       $andPersist Persist object automatically or not.
     *
     * @return object
     */
    public function create($data = null, $andPersist = false);

    /**
     * Delete given object.
     *
     * @param object $object Entity instance.
     */
    public function delete($object);

    /**
     * Update given object.
     *
     * @param object $object     Entity instance.
     * @param bool   $andPersist Persist object automatically or not.
     */
    public function update($object, $andPersist = true);

    /**
     * Checks that concrete manager supports given object.
     *
     * @param object $object Object to check.
     *
     * @throws ModelManagerException
     *
     * @return bool
     */
    public function isSupported($object);

    /**
     * Returns FQCN of the managed class.
     *
     * @return string
     */
    public function getManagedClass();
}
