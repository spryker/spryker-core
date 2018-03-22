<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Kernel;

use Spryker\Service\Kernel\ClassResolver\Factory\FactoryResolver;

class AbstractService
{
    /**
     * @var \Spryker\Service\Kernel\AbstractServiceFactory
     */
    private $factory;

    /**
     * @api
     *
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
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @return \Spryker\Service\Kernel\AbstractServiceFactory
     */
    private function resolveFactory()
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Service\Kernel\ClassResolver\Factory\FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }
}
