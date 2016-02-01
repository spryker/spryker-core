<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Business;

use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;

abstract class AbstractFacade
{

    /**
     * @var BusinessFactoryInterface
     */
    private $factory;

    /**
     * @param AbstractBusinessFactory $factory
     *
     * @return self
     */
    public function setFactory(AbstractBusinessFactory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @return BusinessFactoryInterface
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
     * @return AbstractBusinessFactory
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
