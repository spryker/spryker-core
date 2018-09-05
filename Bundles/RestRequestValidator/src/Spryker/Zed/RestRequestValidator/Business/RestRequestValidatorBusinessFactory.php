<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\RestRequestValidator\Business\Builder\RestRequestValidatorBuilder;
use Spryker\Zed\RestRequestValidator\Business\Builder\RestRequestValidatorBuilderInterface;
use Spryker\Zed\RestRequestValidator\Business\Collector\RestRequestValidatorCollector;
use Spryker\Zed\RestRequestValidator\Business\Collector\RestRequestValidatorCollectorInterface;
use Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder\RestRequestValidatorSchemaFinder;
use Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder\RestRequestValidatorSchemaFinderInterface;
use Spryker\Zed\RestRequestValidator\Business\Merger\RestRequestValidatorMerger;
use Spryker\Zed\RestRequestValidator\Business\Merger\RestRequestValidatorMergerInterface;
use Spryker\Zed\RestRequestValidator\Business\Saver\RestRequestValidatorSaver;
use Spryker\Zed\RestRequestValidator\Business\Saver\RestRequestValidatorSaverInterface;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapterInterface;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapterInterface;
use Spryker\Zed\RestRequestValidator\Dependency\Facade\RestRequestValidatorToStoreFacadeInterface;
use Spryker\Zed\RestRequestValidator\RestRequestValidatorDependencyProvider;

/**
 * @method \Spryker\Zed\RestRequestValidator\RestRequestValidatorConfig getConfig()
 */
class RestRequestValidatorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\RestRequestValidator\Business\Builder\RestRequestValidatorBuilderInterface
     */
    public function createRestRequestValidatorBuilder(): RestRequestValidatorBuilderInterface
    {
        return new RestRequestValidatorBuilder(
            $this->createValidatorCollector(),
            $this->createValidatorMerger(),
            $this->createValidatorSaver(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\RestRequestValidator\Business\Collector\RestRequestValidatorCollectorInterface
     */
    public function createValidatorCollector(): RestRequestValidatorCollectorInterface
    {
        return new RestRequestValidatorCollector(
            $this->createSchemaFinder(),
            $this->getFilesystem(),
            $this->getYaml()
        );
    }

    /**
     * @return \Spryker\Zed\RestRequestValidator\Business\Merger\RestRequestValidatorMergerInterface
     */
    public function createValidatorMerger(): RestRequestValidatorMergerInterface
    {
        return new RestRequestValidatorMerger();
    }

    /**
     * @return \Spryker\Zed\RestRequestValidator\Business\Saver\RestRequestValidatorSaverInterface
     */
    public function createValidatorSaver(): RestRequestValidatorSaverInterface
    {
        return new RestRequestValidatorSaver(
            $this->getFilesystem(),
            $this->getYaml(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder\RestRequestValidatorSchemaFinderInterface
     */
    public function createSchemaFinder(): RestRequestValidatorSchemaFinderInterface
    {
        return new RestRequestValidatorSchemaFinder(
            $this->getFinder(),
            $this->getStoreFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapterInterface
     */
    public function getFinder(): RestRequestValidatorToFinderAdapterInterface
    {
        return $this->getProvidedDependency(RestRequestValidatorDependencyProvider::FINDER);
    }

    /**
     * @return \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface
     */
    public function getFilesystem(): RestRequestValidatorToFilesystemAdapterInterface
    {
        return $this->getProvidedDependency(RestRequestValidatorDependencyProvider::FILESYSTEM);
    }

    /**
     * @return \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapterInterface
     */
    public function getYaml(): RestRequestValidatorToYamlAdapterInterface
    {
        return $this->getProvidedDependency(RestRequestValidatorDependencyProvider::YAML);
    }

    /**
     * @return \Spryker\Zed\RestRequestValidator\Dependency\Facade\RestRequestValidatorToStoreFacadeInterface
     */
    public function getStoreFacade(): RestRequestValidatorToStoreFacadeInterface
    {
        return $this->getProvidedDependency(RestRequestValidatorDependencyProvider::FACADE_STORE);
    }
}
