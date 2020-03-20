<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Checker;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReaderInterface;

class ProductListDeleteChecker implements ProductListDeleteCheckerInterface
{
    protected const ERROR_MESSAGE_UNABLE_TO_DELETE_PRODUCT_LIST = 'Unable to delete Product List since it\'s used by Configurable Bundle Template "%template%" ("%slot%" slot).';

    protected const ERROR_MESSAGE_PARAM_TEMPLATE = '%template%';
    protected const ERROR_MESSAGE_PARAM_SLOT = '%slot%';

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReaderInterface
     */
    protected $configurableBundleTemplateSlotReader;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateSlotReaderInterface $configurableBundleTemplateSlotReader
     */
    public function __construct(ConfigurableBundleTemplateSlotReaderInterface $configurableBundleTemplateSlotReader)
    {
        $this->configurableBundleTemplateSlotReader = $configurableBundleTemplateSlotReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function isProductListDeletable(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ProductListResponseTransfer {
        $configurableBundleTemplateSlotFilterTransfer
            ->requireProductList()
            ->getProductList()
                ->requireIdProductList();

        $productListTransfer = $configurableBundleTemplateSlotFilterTransfer->getProductList();

        $configurableBundleTemplateSlotFilterTransfer = (new ConfigurableBundleTemplateSlotFilterTransfer())
            ->setProductList($configurableBundleTemplateSlotFilterTransfer->getProductList())
            ->setTranslationLocales($configurableBundleTemplateSlotFilterTransfer->getTranslationLocales());

        $configurableBundleTemplateSlotCollectionTransfer = $this->configurableBundleTemplateSlotReader
            ->getConfigurableBundleTemplateSlotCollection($configurableBundleTemplateSlotFilterTransfer);

        return $this->createProductListResponse($productListTransfer, $configurableBundleTemplateSlotCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotCollectionTransfer $configurableBundleTemplateSlotCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function createProductListResponse(
        ProductListTransfer $productListTransfer,
        ConfigurableBundleTemplateSlotCollectionTransfer $configurableBundleTemplateSlotCollectionTransfer
    ): ProductListResponseTransfer {
        $productListResponseTransfer = (new ProductListResponseTransfer())
            ->setProductList($productListTransfer)
            ->setIsSuccessful(true);

        if (!$configurableBundleTemplateSlotCollectionTransfer->getConfigurableBundleTemplateSlots()->count()) {
            return $productListResponseTransfer;
        }

        $productListResponseTransfer = $this->expandProductListResponseWithMessages(
            $productListResponseTransfer,
            $configurableBundleTemplateSlotCollectionTransfer
        );

        return $productListResponseTransfer->setIsSuccessful(false);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotCollectionTransfer $configurableBundleTemplateSlotCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function expandProductListResponseWithMessages(
        ProductListResponseTransfer $productListResponseTransfer,
        ConfigurableBundleTemplateSlotCollectionTransfer $configurableBundleTemplateSlotCollectionTransfer
    ): ProductListResponseTransfer {
        foreach ($configurableBundleTemplateSlotCollectionTransfer->getConfigurableBundleTemplateSlots() as $configurableBundleTemplateSlotTransfer) {
            $configurableBundleTemplateSlotTransfer
                ->requireTranslations()
                ->getConfigurableBundleTemplate()
                    ->requireTranslations();

            $productListResponseTransfer = $this->addMessageToProductListResponse(
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
    protected function addMessageToProductListResponse(
        ProductListResponseTransfer $productListResponseTransfer,
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ProductListResponseTransfer {
        $templateName = $configurableBundleTemplateSlotTransfer
            ->getConfigurableBundleTemplate()
            ->getTranslations()[0]
            ->getName();

        $slotName = $configurableBundleTemplateSlotTransfer
            ->getTranslations()[0]
            ->getName();

        $messageTransfer = (new MessageTransfer())
            ->setValue(static::ERROR_MESSAGE_UNABLE_TO_DELETE_PRODUCT_LIST)
            ->setParameters([
                static::ERROR_MESSAGE_PARAM_TEMPLATE => $templateName,
                static::ERROR_MESSAGE_PARAM_SLOT => $slotName,
            ]);

        $productListResponseTransfer->addMessage($messageTransfer);

        return $productListResponseTransfer;
    }
}
