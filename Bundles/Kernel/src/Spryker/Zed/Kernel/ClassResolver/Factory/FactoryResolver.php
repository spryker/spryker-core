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
    public const CLASS_NAME_PATTERN = '\\%1$s\\Zed\\%2$s%4$s\\%3$s\\%2$s%3$sFactory';

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

        if ($resolved !== null) {
            return $resolved;
        }

        throw new FactoryNotFoundException($this->getClassInfo());
    }

    /**
     * @return string
     */
    public function getClassPattern()
    {
        return sprintf(
            self::CLASS_NAME_PATTERN,
            self::KEY_NAMESPACE,
            self::KEY_BUNDLE,
            self::KEY_LAYER,
            static::KEY_CODE_BUCKET
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
            self::KEY_NAMESPACE => $namespace,
            self::KEY_BUNDLE => $this->getClassInfo()->getModule(),
            self::KEY_LAYER => $this->getClassInfo()->getLayer(),
            static::KEY_CODE_BUCKET => $codeBucket,
        ];

        $className = str_replace(
            array_keys($searchAndReplace),
            array_values($searchAndReplace),
            $this->getClassPattern()
        );

        return $className;
    }
}
