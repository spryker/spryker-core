<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Communication\Form;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\ClassResolver\Config\BundleConfigResolver;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\Kernel\RepositoryResolverAwareTrait;
use Symfony\Component\Form\AbstractType as SymfonyAbstractType;

abstract class AbstractType extends SymfonyAbstractType
{
    use RepositoryResolverAwareTrait;

    /**
     * @var \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    private $facade;

    /**
     * @var \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    private $factory;

    /**
     * @var \Spryker\Zed\Kernel\AbstractBundleConfig
     */
    private $config;

    /**
     * @var \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer
     */
    private $queryContainer;

    /**
     * @param \Spryker\Zed\Kernel\Business\AbstractFacade $facade
     *
     * @return $this
     */
    public function setFacade(AbstractFacade $facade)
    {
        $this->facade = $facade;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        if ($this->facade === null) {
            $this->facade = $this->resolveFacade();
        }

        return $this->facade;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    private function resolveFacade()
    {
        return $this->getFacadeResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver
     */
    private function getFacadeResolver()
    {
        return new FacadeResolver();
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    private function resolveFactory()
    {
        /** @var \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory $factory */
        $factory = $this->getFactoryResolver()->resolve($this);

        return $factory;
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }

    /**
     * @param \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer $queryContainer
     *
     * @return $this
     */
    public function setQueryContainer(AbstractQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer
     */
    protected function getQueryContainer()
    {
        if ($this->queryContainer === null) {
            $this->queryContainer = $this->resolveQueryContainer();
        }

        return $this->queryContainer;
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer
     */
    private function resolveQueryContainer()
    {
        return $this->getQueryContainerResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver
     */
    private function getQueryContainerResolver()
    {
        return new QueryContainerResolver();
    }

    /**
     * @return \Spryker\Zed\Kernel\AbstractBundleConfig
     */
    protected function getConfig()
    {
        if ($this->config === null) {
            $this->config = $this->resolveBundleConfig();
        }

        return $this->config;
    }

    /**
     * @return \Spryker\Zed\Kernel\AbstractBundleConfig
     */
    private function resolveBundleConfig()
    {
        return $this->getBundleConfigResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Config\BundleConfigResolver
     */
    private function getBundleConfigResolver()
    {
        return new BundleConfigResolver();
    }
}
