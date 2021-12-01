<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\ClassResolver\Factory;

use Spryker\Zed\Kernel\ClassResolver\AbstractClassResolver;

/**
 * @deprecated Use {@link \Spryker\Zed\Kernel\ClassResolver\Business\BusinessFactoryResolver} instead.
 * @deprecated Use {@link \Spryker\Zed\Kernel\ClassResolver\Communication\CommunicationFactoryResolver} instead.
 * @deprecated Use {@link \Spryker\Zed\Kernel\ClassResolver\Persistence\PersistenceFactoryResolver} instead.
 */
class FactoryResolver extends AbstractClassResolver
{
    /**
     * @var string
     */
    public const CLASS_NAME_PATTERN = '\\%1$s\\Zed\\%2$s%4$s\\%3$s\\%2$s%3$sFactory';

    /**
     * @var string
     */
    protected const RESOLVABLE_TYPE = 'ZedFactory';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Zed\Kernel\ClassResolver\Factory\FactoryNotFoundException
     *
     * @return object
     */
    public function resolve($callerClass)
    {
        $resolved = parent::doResolve($callerClass);

        if ($resolved === null) {
            throw new FactoryNotFoundException($this->getClassInfo());
        }

        return $resolved;
    }

    /**
     * @return string
     */
    public function getClassPattern()
    {
        return sprintf(
            static::CLASS_NAME_PATTERN,
            static::KEY_NAMESPACE,
            static::KEY_BUNDLE,
            static::KEY_LAYER,
            static::KEY_CODE_BUCKET,
        );
    }

    /**
     * @param string $namespace
     * @param string|null $codeBucket
     *
     * @return string
     */
    protected function buildClassName($namespace, $codeBucket = null)
    {
        $searchAndReplace = [
            static::KEY_NAMESPACE => $namespace,
            static::KEY_BUNDLE => $this->getClassInfo()->getModule(),
            static::KEY_LAYER => $this->getClassInfo()->getLayer(),
            static::KEY_CODE_BUCKET => $codeBucket,
        ];

        $className = str_replace(
            array_keys($searchAndReplace),
            array_values($searchAndReplace),
            $this->getClassPattern(),
        );

        return $className;
    }
}
