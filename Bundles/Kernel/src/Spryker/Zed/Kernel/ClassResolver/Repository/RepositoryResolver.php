<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\ClassResolver\Repository;

use Spryker\Zed\Kernel\ClassResolver\AbstractClassResolver;

class RepositoryResolver extends AbstractClassResolver
{
    protected const RESOLVABLE_TYPE = 'ZedRepository';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Zed\Kernel\ClassResolver\Repository\RepositoryNotFoundException
     *
     * @return \Spryker\Zed\Kernel\Persistence\AbstractRepository
     */
    public function resolve($callerClass)
    {
        $resolved = parent::doResolve($callerClass);

        if ($resolved !== null) {
            return $resolved;
        }

        throw new RepositoryNotFoundException($this->getClassInfo());
    }
}
