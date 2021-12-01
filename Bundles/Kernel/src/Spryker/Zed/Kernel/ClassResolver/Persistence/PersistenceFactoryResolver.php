<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\ClassResolver\Persistence;

use Spryker\Zed\Kernel\ClassResolver\AbstractClassResolver;

class PersistenceFactoryResolver extends AbstractClassResolver
{
    /**
     * @var string
     */
    protected const RESOLVABLE_TYPE = 'ZedFactoryPersistence';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Zed\Kernel\ClassResolver\Persistence\PersistenceFactoryNotFoundException
     *
     * @return \Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory|null $resolved */
        $resolved = parent::doResolve($callerClass);

        if ($resolved === null) {
            throw new PersistenceFactoryNotFoundException($this->getClassInfo());
        }

        return $resolved;
    }
}
