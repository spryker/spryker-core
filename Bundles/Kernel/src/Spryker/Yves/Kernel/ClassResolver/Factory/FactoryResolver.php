<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\ClassResolver\Factory;

use Spryker\Yves\Kernel\ClassResolver\AbstractClassResolver;

class FactoryResolver extends AbstractClassResolver
{
    /**
     * @var string
     */
    protected const RESOLVABLE_TYPE = 'YvesFactory';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Yves\Kernel\ClassResolver\Factory\FactoryNotFoundException
     *
     * @return \Spryker\Yves\Kernel\AbstractFactory
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Yves\Kernel\AbstractFactory|null $resolved */
        $resolved = $this->doResolve($callerClass);
        if ($resolved === null) {
            throw new FactoryNotFoundException($this->getClassInfo());
        }

        return $resolved;
    }
}
