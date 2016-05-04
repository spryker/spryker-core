<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Dependency\Injector;

use Spryker\Shared\Kernel\Dependency\Injector\DependencyInjectorInterface;
use Spryker\Yves\Kernel\ClassResolver\Factory\FactoryResolver;

abstract class AbstractDependencyInjector implements DependencyInjectorInterface
{

    /**
     * @var \Spryker\Yves\Kernel\FactoryInterface
     */
    private $factory;

    /**
     * @return \Spryker\Yves\Kernel\FactoryInterface
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @throws \Spryker\Yves\Kernel\ClassResolver\Factory\FactoryNotFoundException
     *
     * @return \Spryker\Yves\Kernel\AbstractFactory
     */
    private function resolveFactory()
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Yves\Kernel\ClassResolver\Factory\FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }

}
