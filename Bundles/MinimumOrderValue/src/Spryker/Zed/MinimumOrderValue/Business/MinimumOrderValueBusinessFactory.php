<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MinimumOrderValue\Business\Installer\MinimumOrderValueTypeInstaller;
use Spryker\Zed\MinimumOrderValue\Business\Installer\MinimumOrderValueTypeInstallerInterface;
use Spryker\Zed\MinimumOrderValue\Business\StoreThreshold\StoreThresholdReader;
use Spryker\Zed\MinimumOrderValue\Business\StoreThreshold\StoreThresholdReaderInterface;
use Spryker\Zed\MinimumOrderValue\Business\StoreThreshold\StoreThresholdWriter;
use Spryker\Zed\MinimumOrderValue\Business\StoreThreshold\StoreThresholdWriterInterface;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolver;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface;

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
            $this->getConfig()->getMinimumOrderValueStrategies(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\StoreThreshold\StoreThresholdReaderInterface
     */
    public function createStoreThresholdReader(): StoreThresholdReaderInterface
    {
        return new StoreThresholdReader(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\StoreThreshold\StoreThresholdWriterInterface
     */
    public function createStoreThresholdWriter(): StoreThresholdWriterInterface
    {
        return new StoreThresholdWriter(
            $this->createMinimumOrderValueStrategyResolver(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface
     */
    public function createMinimumOrderValueStrategyResolver(): MinimumOrderValueStrategyResolverInterface
    {
        return new MinimumOrderValueStrategyResolver(
            $this->getConfig()->getMinimumOrderValueStrategies()
        );
    }
}
