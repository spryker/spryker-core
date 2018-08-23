<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Installer;

use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface;

class MinimumOrderValueTypeInstaller implements MinimumOrderValueTypeInstallerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueStrategyPluginInterface[]
     */
    protected $minimumOrderValueStrategyPlugins;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface
     */
    protected $minimumOrderValueEntityManager;

    /**
     * @param \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueStrategyPluginInterface[] $minimumOrderValueStrategyPlugins
     * @param \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface $minimumOrderValueEntityManager
     */
    public function __construct(
        array $minimumOrderValueStrategyPlugins,
        MinimumOrderValueEntityManagerInterface $minimumOrderValueEntityManager
    ) {
        $this->minimumOrderValueStrategyPlugins = $minimumOrderValueStrategyPlugins;
        $this->minimumOrderValueEntityManager = $minimumOrderValueEntityManager;
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
        foreach ($this->minimumOrderValueStrategyPlugins as $minimumOrderValueStrategy) {
            $this->minimumOrderValueEntityManager->saveMinimumOrderValueType(
                $minimumOrderValueStrategy->toTransfer()
            );
        }
    }
}
