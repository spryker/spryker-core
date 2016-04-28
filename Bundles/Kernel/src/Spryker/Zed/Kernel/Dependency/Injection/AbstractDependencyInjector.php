<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Dependency\Injection;

use Spryker\Shared\Kernel\Dependency\Injection\DependencyInjector;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;

abstract class AbstractDependencyInjector extends DependencyInjector
{

    /**
     * @var \Spryker\Zed\Kernel\AbstractFactory
     */
    private $factory;

    /**
     * @return \Spryker\Zed\Kernel\AbstractFactory
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @throws \Spryker\Zed\Kernel\ClassResolver\Factory\FactoryNotFoundException
     *
     * @return \Spryker\Zed\Kernel\AbstractFactory
     */
    private function resolveFactory()
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }

}
