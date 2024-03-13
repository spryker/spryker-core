<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Processor\Generator;

use Generated\Shared\Transfer\DynamicFixturesRequestBackendApiAttributesTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\TestifyBackendApi\Processor\Locator\CodeceptionModuleContainerInterface;
use Spryker\Glue\TestifyBackendApi\Processor\Resolver\OperationStrategyResolverInterface;
use Spryker\Glue\TestifyBackendApi\Processor\ResponseBuilder\DynamicFixtureResponseBuilderInterface;
use Spryker\Glue\TestifyBackendApi\Processor\Runner\OperationPostRunnerInterface;

class DynamicFixtureGenerator implements DynamicFixtureGeneratorInterface
{
    /**
     * @var \Spryker\Glue\TestifyBackendApi\Processor\Locator\CodeceptionModuleContainerInterface
     */
    protected CodeceptionModuleContainerInterface $codeceptionModuleContainer;

    /**
     * @var \Spryker\Glue\TestifyBackendApi\Processor\Resolver\OperationStrategyResolverInterface
     */
    protected OperationStrategyResolverInterface $operationStrategyResolver;

    /**
     * @var \Spryker\Glue\TestifyBackendApi\Processor\Runner\OperationPostRunnerInterface
     */
    protected OperationPostRunnerInterface $operationPostRunner;

    /**
     * @var \Spryker\Glue\TestifyBackendApi\Processor\ResponseBuilder\DynamicFixtureResponseBuilderInterface
     */
    protected DynamicFixtureResponseBuilderInterface $dynamicFixtureResponseBuilder;

    /**
     * @param \Spryker\Glue\TestifyBackendApi\Processor\Locator\CodeceptionModuleContainerInterface $codeceptionModuleContainer
     * @param \Spryker\Glue\TestifyBackendApi\Processor\Resolver\OperationStrategyResolverInterface $operationStrategyResolver
     * @param \Spryker\Glue\TestifyBackendApi\Processor\Runner\OperationPostRunnerInterface $operationPostRunner
     * @param \Spryker\Glue\TestifyBackendApi\Processor\ResponseBuilder\DynamicFixtureResponseBuilderInterface $dynamicFixtureResponseBuilder
     */
    public function __construct(
        CodeceptionModuleContainerInterface $codeceptionModuleContainer,
        OperationStrategyResolverInterface $operationStrategyResolver,
        OperationPostRunnerInterface $operationPostRunner,
        DynamicFixtureResponseBuilderInterface $dynamicFixtureResponseBuilder
    ) {
        $this->codeceptionModuleContainer = $codeceptionModuleContainer;
        $this->operationStrategyResolver = $operationStrategyResolver;
        $this->operationPostRunner = $operationPostRunner;
        $this->dynamicFixtureResponseBuilder = $dynamicFixtureResponseBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicFixturesRequestBackendApiAttributesTransfer $dynamicFixturesRequestBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function generate(
        DynamicFixturesRequestBackendApiAttributesTransfer $dynamicFixturesRequestBackendApiAttributesTransfer
    ): GlueResponseTransfer {
        if ($dynamicFixturesRequestBackendApiAttributesTransfer->getOperations()->count() === 0) {
            return $this->dynamicFixtureResponseBuilder->createDynamicFixtureResponse([]);
        }

        $dynamicFixtures = $this->runOperations($dynamicFixturesRequestBackendApiAttributesTransfer);
        $this->operationPostRunner->executePostOperations($dynamicFixturesRequestBackendApiAttributesTransfer);

        return $this->dynamicFixtureResponseBuilder->createDynamicFixtureResponse($dynamicFixtures);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicFixturesRequestBackendApiAttributesTransfer $dynamicFixturesRequestBackendApiAttributesTransfer
     *
     * @return array<string, \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\ArrayObject<array-key, \Spryker\Shared\Kernel\Transfer\AbstractTransfer>|null>
     */
    protected function runOperations(
        DynamicFixturesRequestBackendApiAttributesTransfer $dynamicFixturesRequestBackendApiAttributesTransfer
    ): array {
        $dynamicFixtures = [];
        $moduleContainer = $this->codeceptionModuleContainer->initModuleContainer();

        foreach ($dynamicFixturesRequestBackendApiAttributesTransfer->getOperations() as $dynamicFixtureOperationTransfer) {
            $dynamicFixtureOutput = $this->operationStrategyResolver->execute(
                $dynamicFixtureOperationTransfer,
                $moduleContainer,
            );

            $dynamicFixtureOperationKey = $dynamicFixtureOperationTransfer->getKey();
            if ($dynamicFixtureOperationKey) {
                $dynamicFixtures[$dynamicFixtureOperationKey] = $dynamicFixtureOutput;
            }
        }

        return $dynamicFixtures;
    }
}
