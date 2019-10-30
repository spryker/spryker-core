<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Cleaner;

use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface;
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
     * @var \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface
     */
    protected $configurableBundleTemplateReader;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager
     * @param \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface $configurableBundleTemplateReader
     */
    public function __construct(
        ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager,
        ConfigurableBundleTemplateReaderInterface $configurableBundleTemplateReader
    ) {
        $this->configurableBundleEntityManager = $configurableBundleEntityManager;
        $this->configurableBundleTemplateReader = $configurableBundleTemplateReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function deleteConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($configurableBundleTemplateFilterTransfer) {
            return $this->executeDeleteConfigurableBundleTemplateByIdTransaction($configurableBundleTemplateFilterTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    protected function executeDeleteConfigurableBundleTemplateByIdTransaction(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        $configurableBundleTemplateResponseTransfer = $this->configurableBundleTemplateReader
            ->getConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);

        if (!$configurableBundleTemplateResponseTransfer->getIsSuccessful()) {
            return $configurableBundleTemplateResponseTransfer;
        }

        $idConfigurableBundleTemplate = $configurableBundleTemplateResponseTransfer
            ->getConfigurableBundleTemplate()
            ->getIdConfigurableBundleTemplate();

        $this->configurableBundleEntityManager->deleteConfigurableBundleTemplateSlotsByIdTemplate($idConfigurableBundleTemplate);
        $this->configurableBundleEntityManager->deleteConfigurableBundleTemplateById($idConfigurableBundleTemplate);

        // TODO: remove translates

        return (new ConfigurableBundleTemplateResponseTransfer())
            ->setIsSuccessful(true);
    }
}
