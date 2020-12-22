<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Kernel;

use Spryker\Service\Kernel\ClassResolver\Factory\FactoryResolver;

trait FactoryResolverAwareTrait
{
    /**
     * @var \Spryker\Service\Kernel\AbstractServiceFactory|null
     */
    protected $factory;

    /**
     * @param \Spryker\Service\Kernel\AbstractServiceFactory $factory
     *
     * @return $this
     */
    public function setFactory(AbstractServiceFactory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @return \Spryker\Service\Kernel\AbstractServiceFactory
     */
    protected function getFactory(): AbstractServiceFactory
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @return \Spryker\Service\Kernel\AbstractServiceFactory
     */
    protected function resolveFactory(): AbstractServiceFactory
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Service\Kernel\ClassResolver\Factory\FactoryResolver
     */
    protected function getFactoryResolver(): FactoryResolver
    {
        return new FactoryResolver();
    }
}
