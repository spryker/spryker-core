<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Kernel;

use Spryker\Client\Kernel\ClassResolver\Factory\FactoryResolver;

trait FactoryResolverAwareTrait
{
    /**
     * @var \Spryker\Client\Kernel\AbstractFactory|null
     */
    private $factory;

    /**
     * @param \Spryker\Client\Kernel\AbstractFactory $factory
     *
     * @return $this
     */
    public function setFactory(AbstractFactory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @return \Spryker\Client\Kernel\AbstractFactory
     */
    protected function getFactory(): AbstractFactory
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @return \Spryker\Client\Kernel\AbstractFactory
     */
    protected function resolveFactory(): AbstractFactory
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Client\Kernel\ClassResolver\Factory\FactoryResolver
     */
    protected function getFactoryResolver(): FactoryResolver
    {
        return new FactoryResolver();
    }
}
