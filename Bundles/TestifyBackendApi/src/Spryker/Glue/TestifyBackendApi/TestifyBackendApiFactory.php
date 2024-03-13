<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractBackendApiFactory;
use Spryker\Glue\TestifyBackendApi\Dependency\External\TestifyBackendApiToCodeceptionAdapterInterface;
use Spryker\Glue\TestifyBackendApi\Dependency\Facade\TestifyBackendApiToEventBehaviourFacadeInterface;
use Spryker\Glue\TestifyBackendApi\Dependency\Facade\TestifyBackendApiToQueueFacadeInterface;
use Spryker\Glue\TestifyBackendApi\Processor\Generator\DynamicFixtureGenerator;
use Spryker\Glue\TestifyBackendApi\Processor\Generator\DynamicFixtureGeneratorInterface;
use Spryker\Glue\TestifyBackendApi\Processor\Locator\CodeceptionModuleContainer;
use Spryker\Glue\TestifyBackendApi\Processor\Locator\CodeceptionModuleContainerInterface;
use Spryker\Glue\TestifyBackendApi\Processor\Resolver\OperationArgumentsCacheResolver;
use Spryker\Glue\TestifyBackendApi\Processor\Resolver\OperationArgumentsCacheResolverInterface;
use Spryker\Glue\TestifyBackendApi\Processor\Resolver\OperationStrategyResolver;
use Spryker\Glue\TestifyBackendApi\Processor\Resolver\OperationStrategyResolverInterface;
use Spryker\Glue\TestifyBackendApi\Processor\ResponseBuilder\DynamicFixtureResponseBuilder;
use Spryker\Glue\TestifyBackendApi\Processor\ResponseBuilder\DynamicFixtureResponseBuilderInterface;
use Spryker\Glue\TestifyBackendApi\Processor\Runner\OperationPostRunner;
use Spryker\Glue\TestifyBackendApi\Processor\Runner\OperationPostRunnerInterface;
use Spryker\Glue\TestifyBackendApi\Processor\Strategy\ArrayObjectOperationStrategy;
use Spryker\Glue\TestifyBackendApi\Processor\Strategy\BuilderOperationStrategy;
use Spryker\Glue\TestifyBackendApi\Processor\Strategy\CliCommandOperationStrategy;
use Spryker\Glue\TestifyBackendApi\Processor\Strategy\HelperOperationStrategy;
use Spryker\Glue\TestifyBackendApi\Processor\Strategy\OperationStrategyInterface;
use Spryker\Glue\TestifyBackendApi\Processor\Strategy\TransferOperationStrategy;
use Spryker\Glue\TestifyBackendApi\Processor\Synchronizer\OperationSynchronizer;
use Spryker\Glue\TestifyBackendApi\Processor\Synchronizer\OperationSynchronizerInterface;

/**
 * @method \Spryker\Glue\TestifyBackendApi\TestifyBackendApiConfig getConfig()
 */
class TestifyBackendApiFactory extends AbstractBackendApiFactory
{
    /**
     * @return \Spryker\Glue\TestifyBackendApi\Processor\Generator\DynamicFixtureGeneratorInterface
     */
    public function createDynamicFixtureGenerator(): DynamicFixtureGeneratorInterface
    {
        return new DynamicFixtureGenerator(
            $this->createCodeceptionModuleContainer(),
            $this->createOperationStrategyResolver(),
            $this->createOperationPostRunner(),
            $this->createDynamicFixtureResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\TestifyBackendApi\Processor\Runner\OperationPostRunnerInterface
     */
    public function createOperationPostRunner(): OperationPostRunnerInterface
    {
        return new OperationPostRunner(
            $this->createOperationSynchronizer(),
        );
    }

    /**
     * @return \Spryker\Glue\TestifyBackendApi\Processor\Synchronizer\OperationSynchronizerInterface
     */
    public function createOperationSynchronizer(): OperationSynchronizerInterface
    {
        return new OperationSynchronizer(
            $this->getEventBehaviourFacade(),
            $this->getQueueFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\TestifyBackendApi\Processor\ResponseBuilder\DynamicFixtureResponseBuilderInterface
     */
    public function createDynamicFixtureResponseBuilder(): DynamicFixtureResponseBuilderInterface
    {
        return new DynamicFixtureResponseBuilder();
    }

    /**
     * @return \Spryker\Glue\TestifyBackendApi\Processor\Resolver\OperationArgumentsCacheResolverInterface
     */
    public function createOperationArgumentsCacheResolver(): OperationArgumentsCacheResolverInterface
    {
        return new OperationArgumentsCacheResolver();
    }

    /**
     * @return \Spryker\Glue\TestifyBackendApi\Processor\Locator\CodeceptionModuleContainerInterface
     */
    public function createCodeceptionModuleContainer(): CodeceptionModuleContainerInterface
    {
        return new CodeceptionModuleContainer(
            $this->getConfig(),
            $this->getCodeceptionAdapter(),
        );
    }

    /**
     * @return array<\Spryker\Glue\TestifyBackendApi\Processor\Strategy\OperationStrategyInterface>
     */
    public function getOperationStrategyList(): array
    {
        return [
            $this->createHelperOperationStrategy(),
            $this->createTransferOperationStrategy(),
            $this->createCliCommandOperationStrategy(),
            $this->createArrayObjectOperationStrategy(),
            $this->createBuilderOperationStrategy(),
        ];
    }

    /**
     * @return \Spryker\Glue\TestifyBackendApi\Processor\Strategy\OperationStrategyInterface
     */
    public function createHelperOperationStrategy(): OperationStrategyInterface
    {
        return new HelperOperationStrategy();
    }

    /**
     * @return \Spryker\Glue\TestifyBackendApi\Processor\Strategy\OperationStrategyInterface
     */
    public function createTransferOperationStrategy(): OperationStrategyInterface
    {
        return new TransferOperationStrategy();
    }

    /**
     * @return \Spryker\Glue\TestifyBackendApi\Processor\Strategy\OperationStrategyInterface
     */
    public function createArrayObjectOperationStrategy(): OperationStrategyInterface
    {
        return new ArrayObjectOperationStrategy();
    }

    /**
     * @return \Spryker\Glue\TestifyBackendApi\Processor\Strategy\OperationStrategyInterface
     */
    public function createBuilderOperationStrategy(): OperationStrategyInterface
    {
        return new BuilderOperationStrategy();
    }

    /**
     * @return \Spryker\Glue\TestifyBackendApi\Processor\Strategy\OperationStrategyInterface
     */
    public function createCliCommandOperationStrategy(): OperationStrategyInterface
    {
        return new CliCommandOperationStrategy();
    }

    /**
     * @return \Spryker\Glue\TestifyBackendApi\Processor\Resolver\OperationStrategyResolverInterface
     */
    public function createOperationStrategyResolver(): OperationStrategyResolverInterface
    {
        return new OperationStrategyResolver(
            $this->getOperationStrategyList(),
            $this->createOperationArgumentsCacheResolver(),
        );
    }

    /**
     * @return \Spryker\Glue\TestifyBackendApi\Dependency\Facade\TestifyBackendApiToEventBehaviourFacadeInterface
     */
    public function getEventBehaviourFacade(): TestifyBackendApiToEventBehaviourFacadeInterface
    {
        return $this->getProvidedDependency(TestifyBackendApiDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Glue\TestifyBackendApi\Dependency\Facade\TestifyBackendApiToQueueFacadeInterface
     */
    public function getQueueFacade(): TestifyBackendApiToQueueFacadeInterface
    {
        return $this->getProvidedDependency(TestifyBackendApiDependencyProvider::FACADE_QUEUE);
    }

    /**
     * @return \Spryker\Glue\TestifyBackendApi\Dependency\External\TestifyBackendApiToCodeceptionAdapterInterface
     */
    public function getCodeceptionAdapter(): TestifyBackendApiToCodeceptionAdapterInterface
    {
        return $this->getProvidedDependency(TestifyBackendApiDependencyProvider::ADAPTER_CODECEPTION);
    }
}
