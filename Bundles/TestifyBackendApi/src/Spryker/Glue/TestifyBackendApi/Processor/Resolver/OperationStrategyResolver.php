<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Processor\Resolver;

use Codeception\Lib\ModuleContainer;
use Generated\Shared\Transfer\DynamicFixtureOperationTransfer;
use Spryker\Glue\TestifyBackendApi\Processor\Exception\OperationStrategyNotFoundException;
use Spryker\Glue\TestifyBackendApi\Processor\Strategy\OperationStrategyInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class OperationStrategyResolver implements OperationStrategyResolverInterface
{
    /**
     * @var array<\Spryker\Glue\TestifyBackendApi\Processor\Strategy\OperationStrategyInterface>
     */
    protected array $operationStrategies;

    /**
     * @var \Spryker\Glue\TestifyBackendApi\Processor\Resolver\OperationArgumentsCacheResolverInterface
     */
    protected OperationArgumentsCacheResolverInterface $operationArgumentsCacheResolver;

    /**
     * @param array<\Spryker\Glue\TestifyBackendApi\Processor\Strategy\OperationStrategyInterface> $operationStrategies
     * @param \Spryker\Glue\TestifyBackendApi\Processor\Resolver\OperationArgumentsCacheResolverInterface $operationArgumentsCacheResolver
     */
    public function __construct(
        array $operationStrategies,
        OperationArgumentsCacheResolverInterface $operationArgumentsCacheResolver
    ) {
        $this->operationStrategies = $operationStrategies;
        $this->operationArgumentsCacheResolver = $operationArgumentsCacheResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicFixtureOperationTransfer $dynamicFixtureOperationTransfer
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\ArrayObject<array-key, \Spryker\Shared\Kernel\Transfer\AbstractTransfer>|null
     */
    public function execute(
        DynamicFixtureOperationTransfer $dynamicFixtureOperationTransfer,
        ModuleContainer $moduleContainer
    ): AbstractTransfer|\ArrayObject|null {
        $operationStrategy = $this->resolveOperationStrategy($dynamicFixtureOperationTransfer->getTypeOrFail());

        $dynamicFixtureOutput = $operationStrategy->execute(
            $dynamicFixtureOperationTransfer,
            $moduleContainer,
            $this->operationArgumentsCacheResolver->resolve($dynamicFixtureOperationTransfer->getArguments()),
        );

        if ($dynamicFixtureOperationTransfer->getKey() && $dynamicFixtureOutput) {
            $this->operationArgumentsCacheResolver->add($dynamicFixtureOperationTransfer->getKeyOrFail(), $dynamicFixtureOutput);
        }

        return $dynamicFixtureOutput;
    }

    /**
     * @param string $operationType
     *
     * @throws \Spryker\Glue\TestifyBackendApi\Processor\Exception\OperationStrategyNotFoundException
     *
     * @return \Spryker\Glue\TestifyBackendApi\Processor\Strategy\OperationStrategyInterface
     */
    protected function resolveOperationStrategy(string $operationType): OperationStrategyInterface
    {
        foreach ($this->operationStrategies as $operationStrategy) {
            if ($operationStrategy->getType() === $operationType) {
                return $operationStrategy;
            }
        }

        throw new OperationStrategyNotFoundException(sprintf(
            'Operation strategy for type "%s" not found',
            $operationType,
        ));
    }
}
