<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Reader;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateSlotProductListExpanderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface;

class ConfigurableBundleTemplateSlotReader implements ConfigurableBundleTemplateSlotReaderInterface
{
    protected const ERROR_MESSAGE_UNBABLE_TO_DELETE_PRODUCT_LIST = 'Unable to delete Product List since it\'s used by Configurable Bundle Template "%template%" ("%slot%" slot).';

    protected const ERROR_MESSAGE_PARAM_TEMPLATE = '%template%';
    protected const ERROR_MESSAGE_PARAM_SLOT = '%slot%';

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface
     */
    protected $configurableBundleRepository;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface
     */
    protected $configurableBundleTranslationExpander;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateSlotProductListExpanderInterface
     */
    protected $configurableBundleTemplateSlotProductListExpander;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface $configurableBundleRepository
     * @param \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface $configurableBundleTranslationExpander
     * @param \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateSlotProductListExpanderInterface $configurableBundleTemplateSlotProductListExpander
     */
    public function __construct(
        ConfigurableBundleRepositoryInterface $configurableBundleRepository,
        ConfigurableBundleTranslationExpanderInterface $configurableBundleTranslationExpander,
        ConfigurableBundleTemplateSlotProductListExpanderInterface $configurableBundleTemplateSlotProductListExpander
    ) {
        $this->configurableBundleRepository = $configurableBundleRepository;
        $this->configurableBundleTranslationExpander = $configurableBundleTranslationExpander;
        $this->configurableBundleTemplateSlotProductListExpander = $configurableBundleTemplateSlotProductListExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer|null
     */
    public function findConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ?ConfigurableBundleTemplateSlotTransfer {
        $configurableBundleTemplateSlotTransfer = $this->configurableBundleRepository
            ->findConfigurableBundleTemplateSlot($configurableBundleTemplateSlotFilterTransfer);

        if (!$configurableBundleTemplateSlotTransfer) {
            return null;
        }

        $configurableBundleTemplateSlotTransfer = $this->configurableBundleTemplateSlotProductListExpander
            ->expandConfigurableBundleTemplateSlotWithProductList($configurableBundleTemplateSlotTransfer);

        return $configurableBundleTemplateSlotTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[]
     */
    public function getConfigurableBundleTemplateSlotCollection(ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer): array
    {
        $configurableBundleTemplateSlotTransfers = $this->configurableBundleRepository
            ->getConfigurableBundleTemplateSlotCollection($configurableBundleTemplateSlotFilterTransfer);

        return $this->expandConfigurableBundleTemplateSlotTransfersWithTranslations(
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
        $productListTransfer->requireIdProductList();

        $configurableBundleTemplateSlotFilterTransfer = (new ConfigurableBundleTemplateSlotFilterTransfer())->setIdProductList(
            $productListTransfer->getIdProductList()
        );

        $configurableBundleTemplateSlotTransfers = $this->getConfigurableBundleTemplateSlotCollection($configurableBundleTemplateSlotFilterTransfer);

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
        $productListResponseTransfer = (new ProductListResponseTransfer())
            ->setProductList($productListTransfer)
            ->setIsSuccessful(true);

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
    protected function expandConfigurableBundleTemplateSlotTransfersWithTranslations(array $configurableBundleTemplateSlotTransfers): array
    {
        foreach ($configurableBundleTemplateSlotTransfers as $configurableBundleTemplateSlotTransfer) {
            $configurableBundleTemplateSlotTransfer = $this->configurableBundleTranslationExpander
                ->expandConfigurableBundleTemplateSlotWithTranslationForCurrentLocale($configurableBundleTemplateSlotTransfer);

            $configurableBundleTemplateSlotTransfer->setConfigurableBundleTemplate(
                $this->configurableBundleTranslationExpander->expandConfigurableBundleTemplateWithTranslationForCurrentLocale(
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
                    static::ERROR_MESSAGE_PARAM_TEMPLATE => $templateName,
                    static::ERROR_MESSAGE_PARAM_SLOT => $slotName,
                ])
        );

        return $productListResponseTransfer;
    }
}
