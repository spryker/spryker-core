<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MinimumOrderValue\Business\Installer\MinimumOrderValueTypeInstaller;
use Spryker\Zed\MinimumOrderValue\Business\Installer\MinimumOrderValueTypeInstallerInterface;
use Spryker\Zed\MinimumOrderValue\Business\StoreThreshold\StoreThresholdManager;
use Spryker\Zed\MinimumOrderValue\Business\StoreThreshold\StoreThresholdManagerInterface;
use Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyResolver;
use Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyResolverInterface;
use Spryker\Zed\MinimumOrderValue\MinimumOrderValueDependencyProvider;

/**
 * @method \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueRepositoryInterface getRepository()
 * @method \Spryker\Zed\MinimumOrderValue\MinimumOrderValueConfig getConfig()
 */
class MinimumOrderValueBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\Installer\MinimumOrderValueTypeInstallerInterface
     */
    public function createMinimumOrderValueTypeInstaller(): MinimumOrderValueTypeInstallerInterface
    {
        return new MinimumOrderValueTypeInstaller(
            $this->getMinimumOrderValueStrategies(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface[]
     */
    public function getMinimumOrderValueStrategies(): array
    {
        return $this->getProvidedDependency(MinimumOrderValueDependencyProvider::MINIMUM_ORDER_VALUE_STRATEGIES);
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\StoreThreshold\StoreThresholdManagerInterface
     */
    public function createStoreThresholdManager(): StoreThresholdManagerInterface
    {
        return new StoreThresholdManager(
            $this->createMinimumOrderValueStrategyResolver(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyResolverInterface
     */
    protected function createMinimumOrderValueStrategyResolver(): MinimumOrderValueStrategyResolverInterface
    {
        return new MinimumOrderValueStrategyResolver(
            $this->getMinimumOrderValueStrategies()
        );
    }
}
