<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Installer;

use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface;
use Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface;

class MinimumOrderValueTypeInstaller implements MinimumOrderValueTypeInstallerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface[]
     */
    protected $minimumOrderValueStrategies;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface[] $minimumOrderValueStrategies
     * @param \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface $entityManager
     */
    public function __construct(
        array $minimumOrderValueStrategies,
        MinimumOrderValueEntityManagerInterface $entityManager
    ) {
        $this->minimumOrderValueStrategies = $minimumOrderValueStrategies;
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
        foreach ($this->minimumOrderValueStrategies as $minimumOrderValueStrategy) {
            $this->entityManager->saveMinimumOrderValueType(
                $this->createMinimumOrderValueTypeTransfer($minimumOrderValueStrategy)
            );
        }
    }

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface $minimumOrderValueStrategy
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer
     */
    protected function createMinimumOrderValueTypeTransfer(
        MinimumOrderValueStrategyInterface $minimumOrderValueStrategy
    ): MinimumOrderValueTypeTransfer {
        return (new MinimumOrderValueTypeTransfer())->setName($minimumOrderValueStrategy->getName());
    }
}
