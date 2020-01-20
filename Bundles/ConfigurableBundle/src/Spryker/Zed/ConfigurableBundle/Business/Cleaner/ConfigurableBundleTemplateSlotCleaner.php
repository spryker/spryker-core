<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Cleaner;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReaderInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ConfigurableBundleTemplateSlotCleaner implements ConfigurableBundleTemplateSlotCleanerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface
     */
    protected $configurableBundleEntityManager;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReaderInterface
     */
    protected $configurableBundleTemplateSlotReader;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager
     * @param \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReaderInterface $configurableBundleTemplateSlotReader
     */
    public function __construct(
        ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager,
        ConfigurableBundleTemplateSlotReaderInterface $configurableBundleTemplateSlotReader
    ) {
        $this->configurableBundleEntityManager = $configurableBundleEntityManager;
        $this->configurableBundleTemplateSlotReader = $configurableBundleTemplateSlotReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer
     */
    public function deleteConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ConfigurableBundleTemplateSlotResponseTransfer {
        $configurableBundleTemplateSlotResponseTransfer = $this->configurableBundleTemplateSlotReader
            ->getConfigurableBundleTemplateSlot($configurableBundleTemplateSlotFilterTransfer);

        if (!$configurableBundleTemplateSlotResponseTransfer->getIsSuccessful()) {
            return $configurableBundleTemplateSlotResponseTransfer;
        }

        $idConfigurableBundleTemplateSlot = $configurableBundleTemplateSlotResponseTransfer
            ->getConfigurableBundleTemplateSlot()
            ->getIdConfigurableBundleTemplateSlot();

        $this->configurableBundleEntityManager->deleteConfigurableBundleTemplateSlotById($idConfigurableBundleTemplateSlot);

        return (new ConfigurableBundleTemplateSlotResponseTransfer())
            ->setIsSuccessful(true);
    }
}
