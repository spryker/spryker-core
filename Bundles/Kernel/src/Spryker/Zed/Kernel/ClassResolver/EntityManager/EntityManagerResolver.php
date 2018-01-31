<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\ClassResolver\EntityManager;

use Spryker\Zed\Kernel\ClassResolver\AbstractClassResolver;

class EntityManagerResolver extends AbstractClassResolver
{
    const CLASS_NAME_PATTERN = '\\%1$s\\Zed\\%2$s%3$s\\Persistence\\%2$sEntityManager';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Zed\Kernel\ClassResolver\EntityManager\EntityManagerNotFoundException
     *
     * @return \Spryker\Zed\Kernel\Persistence\AbstractRepository
     */
    public function resolve($callerClass)
    {
        $this->setCallerClass($callerClass);
        if ($this->canResolve()) {
            return $this->getResolvedClassInstance();
        }

        throw new EntityManagerNotFoundException($this->getClassInfo());
    }

    /**
     * @return string
     */
    protected function getClassPattern()
    {
        return sprintf(
            self::CLASS_NAME_PATTERN,
            self::KEY_NAMESPACE,
            self::KEY_BUNDLE,
            self::KEY_STORE
        );
    }

    /**
     * @param string $namespace
     * @param string|null $store
     *
     * @return string
     */
    protected function buildClassName($namespace, $store = null)
    {
        $searchAndReplace = [
            self::KEY_NAMESPACE => $namespace,
            self::KEY_BUNDLE => $this->getClassInfo()->getBundle(),
            self::KEY_STORE => $store,
        ];

        $className = str_replace(
            array_keys($searchAndReplace),
            array_values($searchAndReplace),
            $this->getClassPattern()
        );

        return $className;
    }
}
