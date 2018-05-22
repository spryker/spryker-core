<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Installer;

use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitEntityManagerInterface;
use Spryker\Zed\ProductMeasurementUnit\ProductMeasurementUnitConfig;

class ProductMeasurementUnitInstaller implements ProductMeasurementUnitInstallerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\ProductMeasurementUnitConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\ProductMeasurementUnitConfig $config
     * @param \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitEntityManagerInterface $entityManager
     */
    public function __construct(
        ProductMeasurementUnitConfig $config,
        ProductMeasurementUnitEntityManagerInterface $entityManager
    ) {
        $this->config = $config;
        $this->entityManager = $entityManager;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $this->getTransactionHandler()->handleTransaction(function () {
            $this->executeInstallTransaction();
        });
    }

    /**
     * @return void
     */
    protected function executeInstallTransaction(): void
    {
        $productInfrastructuralMeasurementUnits = $this->config->getInfrastructuralMeasurementUnits();

        foreach ($productInfrastructuralMeasurementUnits as $productMeasurementUnitTransfer) {
            $this->entityManager->saveProductMeasurementUnit($productMeasurementUnitTransfer);
        }
    }
}
