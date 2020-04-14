<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Cleaner;

use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductImageFacadeInterface;
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
     * @var \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductImageFacadeInterface
     */
    protected $productImageFacade;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager
     * @param \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface $configurableBundleTemplateReader
     * @param \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductImageFacadeInterface $productImageFacade
     */
    public function __construct(
        ConfigurableBundleEntityManagerInterface $configurableBundleEntityManager,
        ConfigurableBundleTemplateReaderInterface $configurableBundleTemplateReader,
        ConfigurableBundleToProductImageFacadeInterface $productImageFacade
    ) {
        $this->configurableBundleEntityManager = $configurableBundleEntityManager;
        $this->configurableBundleTemplateReader = $configurableBundleTemplateReader;
        $this->productImageFacade = $productImageFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function deleteConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        $configurableBundleTemplateResponseTransfer = $this->configurableBundleTemplateReader
            ->getConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);

        if (!$configurableBundleTemplateResponseTransfer->getIsSuccessful()) {
            return $configurableBundleTemplateResponseTransfer;
        }

        $configurableBundleTemplateTransfer = $configurableBundleTemplateResponseTransfer->getConfigurableBundleTemplate();

        return $this->getTransactionHandler()->handleTransaction(function () use ($configurableBundleTemplateTransfer) {
            return $this->executeDeleteConfigurableBundleTemplateTransaction($configurableBundleTemplateTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    protected function executeDeleteConfigurableBundleTemplateTransaction(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        $idConfigurableBundleTemplate = $configurableBundleTemplateTransfer
            ->requireIdConfigurableBundleTemplate()
                ->getIdConfigurableBundleTemplate();

        $this->deleteProductImageSets($configurableBundleTemplateTransfer);
        $this->configurableBundleEntityManager->deleteConfigurableBundleTemplateSlotsByIdTemplate($idConfigurableBundleTemplate);
        $this->configurableBundleEntityManager->deleteConfigurableBundleTemplateById($idConfigurableBundleTemplate);

        return (new ConfigurableBundleTemplateResponseTransfer())
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return void
     */
    protected function deleteProductImageSets(ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer): void
    {
        foreach ($configurableBundleTemplateTransfer->getProductImageSets() as $productImageSetTransfer) {
            $this->productImageFacade->deleteProductImageSet($productImageSetTransfer);
        }
    }
}
