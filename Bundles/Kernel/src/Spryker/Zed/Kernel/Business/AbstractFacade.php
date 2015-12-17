<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Business;

use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

abstract class AbstractFacade implements FacadeInterface
{

    /**
     * @var BusinessFactoryInterface
     */
    private $businessFactory;

    /**
     * @var AbstractQueryContainer
     */
    private $queryContainer;

    /**
     * @var Container
     */
    private $container;

    /**
     * @param AbstractBusinessFactory $businessFactory
     *
     * @return self
     */
    public function setBusinessFactory(AbstractBusinessFactory $businessFactory)
    {
        $this->businessFactory = $businessFactory;

        return $this;
    }

    /**
     * @param AbstractQueryContainer $queryContainer
     *
     * @return self
     */
    public function setQueryContainer(AbstractQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;

        return $this;
    }

    /**
     * @param Container $container
     *
     * @return self
     */
    public function setExternalDependencies(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @return Container
     */
    protected function getContainer()
    {
        return $this->container;
    }

    /**
     * @return BusinessFactoryInterface
     */
    protected function getFactory()
    {
        if ($this->businessFactory === null) {
            $this->businessFactory = $this->resolveBusinessFactory();
        }

        if ($this->container !== null) {
            $this->businessFactory->setContainer($this->container);
        }

        if ($this->queryContainer !== null) {
            $this->businessFactory->setQueryContainer($this->queryContainer);
        }

        return $this->businessFactory;
    }

    /**
     * @throws FactoryNotFoundException
     *
     * @return AbstractBusinessFactory
     */
    protected function resolveBusinessFactory()
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return FactoryResolver
     */
    protected function getFactoryResolver()
    {
        return new FactoryResolver();
    }

}
