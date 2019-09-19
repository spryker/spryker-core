<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Reader;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Hydrator\ConfigurableBundleTranslationHydratorInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface;

class ConfigurableBundleTemplateSlotReader implements ConfigurableBundleTemplateSlotReaderInterface
{
    protected const ERROR_MESSAGE_UNBABLE_TO_DELETE_PRODUCT_LIST = 'Unable to delete Product List since it used by Configurable Bundle Template "%template%" ("%slot%" slot).';

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface
     */
    protected $configurableBundleRepository;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Hydrator\ConfigurableBundleTranslationHydratorInterface
     */
    protected $configurableBundleTranslationHydrator;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface $configurableBundleRepository
     * @param \Spryker\Zed\ConfigurableBundle\Business\Hydrator\ConfigurableBundleTranslationHydratorInterface $configurableBundleTranslationHydrator
     */
    public function __construct(
        ConfigurableBundleRepositoryInterface $configurableBundleRepository,
        ConfigurableBundleTranslationHydratorInterface $configurableBundleTranslationHydrator
    ) {
        $this->configurableBundleRepository = $configurableBundleRepository;
        $this->configurableBundleTranslationHydrator = $configurableBundleTranslationHydrator;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[]
     */
    public function findConfigurableBundleTemplateSlotsByProductList(ProductListTransfer $productListTransfer): array
    {
        $productListTransfer->requireIdProductList();

        $configurableBundleTemplateSlotTransfers = $this->configurableBundleRepository
            ->findConfigurableBundleTemplateSlotsByIdProductList($productListTransfer->getIdProductList());

        return $this->hydrateConfigurableBundleTemplateSlotTransfersWithTranslations(
            $configurableBundleTemplateSlotTransfers
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function checkProductListUsageAmongSlots(ProductListTransfer $productListTransfer): ProductListResponseTransfer
    {
        $configurableBundleTemplateSlotTransfers = $this->findConfigurableBundleTemplateSlotsByProductList($productListTransfer);

        return $this->createProductListResponseTransfer($productListTransfer, $configurableBundleTemplateSlotTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[] $configurableBundleTemplateSlotTransfers
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function createProductListResponseTransfer(ProductListTransfer $productListTransfer, array $configurableBundleTemplateSlotTransfers): ProductListResponseTransfer
    {
        $productListResponseTransfer = (new ProductListResponseTransfer())->setProductList()->setIsSuccessful(true);

        if (!$configurableBundleTemplateSlotTransfers) {
            return $productListResponseTransfer;
        }

        $productListResponseTransfer = $this->expandProductListResponseTransferWithMessages($productListResponseTransfer, $configurableBundleTemplateSlotTransfers);

        return $productListResponseTransfer->setIsSuccessful(false);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[] $configurableBundleTemplateSlotTransfers
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[]
     */
    protected function hydrateConfigurableBundleTemplateSlotTransfersWithTranslations(array $configurableBundleTemplateSlotTransfers): array
    {
        foreach ($configurableBundleTemplateSlotTransfers as $configurableBundleTemplateSlotTransfer) {
            $configurableBundleTemplateSlotTransfer = $this->configurableBundleTranslationHydrator
                ->hydrateConfigurableBundleTemplateSlotWithTranslationForCurrentLocale($configurableBundleTemplateSlotTransfer);

            $configurableBundleTemplateSlotTransfer->setConfigurableBundleTemplate(
                $this->configurableBundleTranslationHydrator->hydrateConfigurableBundleTemplateWithTranslationForCurrentLocale(
                    $configurableBundleTemplateSlotTransfer->getConfigurableBundleTemplate()
                )
            );
        }

        return $configurableBundleTemplateSlotTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[] $configurableBundleTemplateSlotTransfers
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function expandProductListResponseTransferWithMessages(
        ProductListResponseTransfer $productListResponseTransfer,
        array $configurableBundleTemplateSlotTransfers
    ): ProductListResponseTransfer {
        foreach ($configurableBundleTemplateSlotTransfers as $configurableBundleTemplateSlotTransfer) {
            $configurableBundleTemplateSlotTransfer->requireTranslations();
            $configurableBundleTemplateSlotTransfer->getConfigurableBundleTemplate()->requireTranslations();

            $productListResponseTransfer = $this->addMessageToProductListResponseTransfer(
                $productListResponseTransfer,
                $configurableBundleTemplateSlotTransfer
            );
        }

        return $productListResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function addMessageToProductListResponseTransfer(
        ProductListResponseTransfer $productListResponseTransfer,
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ProductListResponseTransfer {
        $templateName = $configurableBundleTemplateSlotTransfer->getConfigurableBundleTemplate()->getTranslations()[0]->getName();
        $slotName = $configurableBundleTemplateSlotTransfer->getTranslations()[0]->getName();

        $productListResponseTransfer->addMessage(
            (new MessageTransfer())->setValue(static::ERROR_MESSAGE_UNBABLE_TO_DELETE_PRODUCT_LIST)
                ->setParameters([
                    '%template%' => $templateName,
                    '%slot%' => $slotName,
                ])
        );

        return $productListResponseTransfer;
    }
}
