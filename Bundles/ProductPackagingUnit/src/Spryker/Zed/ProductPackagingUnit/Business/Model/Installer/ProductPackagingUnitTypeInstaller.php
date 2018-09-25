<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Installer;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeReaderInterface;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitEntityManagerInterface;
use Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig;

class ProductPackagingUnitTypeInstaller implements ProductPackagingUnitTypeInstallerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeReaderInterface
     */
    protected $reader;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitEntityManagerInterface $entityManager
     * @param \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig $config
     * @param \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeReaderInterface $reader
     */
    public function __construct(
        ProductPackagingUnitEntityManagerInterface $entityManager,
        ProductPackagingUnitConfig $config,
        ProductPackagingUnitTypeReaderInterface $reader
    ) {
        $this->entityManager = $entityManager;
        $this->config = $config;
        $this->reader = $reader;
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
        $productInfrastructuralPackagingUnitTypes = $this->config->getInfrastructuralPackagingUnitTypes();

        foreach ($productInfrastructuralPackagingUnitTypes as $productInfrastructuralPackagingUnitTypeTransfer) {
            if (!$this->isExistProductPackagingUnitType($productInfrastructuralPackagingUnitTypeTransfer)) {
                $this->entityManager->saveProductPackagingUnitType($productInfrastructuralPackagingUnitTypeTransfer);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return bool
     */
    protected function isExistProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): bool {
        return $this->reader->findProductPackagingUnitTypeByName($productPackagingUnitTypeTransfer) !== null;
    }
}
