<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel;

use Spryker\Zed\Kernel\ClassResolver\Repository\RepositoryResolver;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

trait RepositoryResolverAwareTrait
{
    /**
     * @var \Spryker\Zed\Kernel\Persistence\AbstractRepository
     */
    private $repository;

    /**
     * @param \Spryker\Zed\Kernel\Persistence\AbstractRepository $repository
     *
     * @return $this
     */
    public function setRepository(AbstractRepository $repository)
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\AbstractRepository
     */
    protected function getRepository()
    {
        if ($this->repository === null) {
            $this->repository = $this->resolveRepository();
        }

        return $this->repository;
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\AbstractRepository
     */
    private function resolveRepository()
    {
        return $this->getRepositoryResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Repository\RepositoryResolver
     */
    private function getRepositoryResolver()
    {
        return new RepositoryResolver();
    }
}
