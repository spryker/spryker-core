<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\ClassResolver\Factory;

use Spryker\Glue\Kernel\ClassResolver\AbstractClassResolver;

class FactoryResolver extends AbstractClassResolver
{
    /**
     * @var string
     */
    protected const RESOLVABLE_TYPE = 'GlueFactory';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Glue\Kernel\ClassResolver\Factory\FactoryNotFoundException
     *
     * @return \Spryker\Glue\Kernel\AbstractFactory
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Glue\Kernel\AbstractFactory|null $resolved */
        $resolved = $this->doResolve($callerClass);
        if ($resolved === null) {
            throw new FactoryNotFoundException($this->getClassInfo());
        }

        return $resolved;
    }
}
