<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\ClassResolver\EntityManager;

use Spryker\Zed\Kernel\ClassResolver\AbstractClassResolver;

class EntityManagerResolver extends AbstractClassResolver
{
    protected const RESOLVABLE_TYPE = 'ZedEntityManager';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Zed\Kernel\ClassResolver\EntityManager\EntityManagerNotFoundException
     *
     * @return \Spryker\Zed\Kernel\Persistence\AbstractEntityManager
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Zed\Kernel\Persistence\AbstractEntityManager $resolved */
        $resolved = parent::doResolve($callerClass);

        if ($resolved !== null) {
            return $resolved;
        }

        throw new EntityManagerNotFoundException($this->getClassInfo());
    }
}
