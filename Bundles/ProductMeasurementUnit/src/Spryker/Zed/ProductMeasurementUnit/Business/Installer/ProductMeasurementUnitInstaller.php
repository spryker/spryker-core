<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Installer;

use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToEventFacadeInterface;
use Spryker\Zed\ProductMeasurementUnit\Dependency\ProductMeasurementUnitEvents;
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
     * @var \Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\ProductMeasurementUnitConfig $config
     * @param \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitEntityManagerInterface $entityManager
     * @param \Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToEventFacadeInterface $eventFacade
     */
    public function __construct(
        ProductMeasurementUnitConfig $config,
        ProductMeasurementUnitEntityManagerInterface $entityManager,
        ProductMeasurementUnitToEventFacadeInterface $eventFacade
    ) {
        $this->config = $config;
        $this->entityManager = $entityManager;
        $this->eventFacade = $eventFacade;
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

        $savedEntityIds = [];
        foreach ($productInfrastructuralMeasurementUnits as $productMeasurementUnitTransfer) {
            $savedProductMeasurementUnitTransfer = $this->entityManager->saveProductMeasurementUnit($productMeasurementUnitTransfer);
            $savedEntityIds[] = $savedProductMeasurementUnitTransfer->getIdProductMeasurementUnit();
        }

        $this->publishMeasurementUnits($savedEntityIds);
    }

    /**
     * @param array $savedEntityIds
     *
     * @return void
     */
    protected function publishMeasurementUnits(array $savedEntityIds): void
    {
        $savedEntityIds = array_unique($savedEntityIds);

        foreach ($savedEntityIds as $savedEntityId) {
            $this->eventFacade->trigger(
                ProductMeasurementUnitEvents::PRODUCT_MEASUREMENT_UNIT_PUBLISH,
                (new EventEntityTransfer())->setId($savedEntityId)
            );
        }
    }
}
