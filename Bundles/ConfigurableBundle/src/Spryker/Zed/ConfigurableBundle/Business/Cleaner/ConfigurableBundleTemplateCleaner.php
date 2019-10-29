<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Cleaner;

use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ConfigurableBundleTemplateCleaner implements ConfigurableBundleTemplateCleanerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface
     */
    protected $configurableBundleEntityManager;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager
     */
    public function __construct(ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager)
    {
        $this->configurableBundleEntityManager = $configurableBundleEntityManager;
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    public function deleteConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($idConfigurableBundleTemplate) {
            $this->executeDeleteConfigurableBundleTemplateByIdTransaction($idConfigurableBundleTemplate);
        });
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    protected function executeDeleteConfigurableBundleTemplateByIdTransaction(int $idConfigurableBundleTemplate): void
    {
        $this->configurableBundleEntityManager->deleteConfigurableBundleTemplateSlotsByIdTemplate($idConfigurableBundleTemplate);
        $this->configurableBundleEntityManager->deleteConfigurableBundleTemplateById($idConfigurableBundleTemplate);
    }
}
