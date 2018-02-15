<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel;

use Spryker\Zed\Kernel\ClassResolver\EntityManager\EntityManagerResolver;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

trait EntityManagerResolverAwareTrait
{
    /**
     * @var \Spryker\Zed\Kernel\Persistence\AbstractEntityManager
     */
    private $entityManager;

    /**
     * @param \Spryker\Zed\Kernel\Persistence\AbstractEntityManager $entityManager
     *
     * @return $this
     */
    public function setEntityManager(AbstractEntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\AbstractRepository
     */
    protected function getEntityManager()
    {
        if ($this->entityManager === null) {
            $this->entityManager = $this->resolveEntityManager();
        }

        return $this->entityManager;
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\AbstractRepository
     */
    private function resolveEntityManager()
    {
        return $this->getEntityManagerResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\EntityManager\EntityManagerResolver
     */
    private function getEntityManagerResolver()
    {
        return new EntityManagerResolver();
    }
}
