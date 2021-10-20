<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Expander;

use Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductOfferFacadeInterface;

class MerchantOrderItemTableExpander implements MerchantOrderItemTableExpanderInterface
{
    /**
     * @var string
     */
    protected const COL_KEY_MERCHANT_SKU = 'merchantSku';

    /**
     * @var string
     */
    protected const COL_KEY_PRODUCT_OFFER_REFERENCE = 'productOfferReference';

    /**
     * @uses \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface::COLUMN_TYPE_TEXT
     * @var string
     */
    protected const COLUMN_TYPE_TEXT = 'text';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct(ProductOfferMerchantPortalGuiToProductOfferFacadeInterface $productOfferFacade)
    {
        $this->productOfferFacade = $productOfferFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function expandConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationTransfer
            ->addColumn((new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_MERCHANT_SKU)
                ->setTitle('Merchant SKU')
                ->setType(static::COLUMN_TYPE_TEXT)
                ->setSortable(false)
                ->setHideable(true))
            ->addColumn((new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_PRODUCT_OFFER_REFERENCE)
                ->setTitle('Offer Reference')
                ->setType(static::COLUMN_TYPE_TEXT)
                ->setSortable(false)
                ->setHideable(true));

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    public function expandDataResponse(GuiTableDataResponseTransfer $guiTableDataResponseTransfer): GuiTableDataResponseTransfer
    {
        $productOfferReferences = [];

        foreach ($guiTableDataResponseTransfer->getRows() as $guiTableRowDataResponseTransfer) {
            $responseData = $guiTableRowDataResponseTransfer->getResponseData();

            /** @var \Generated\Shared\Transfer\GuiTableDataResponsePayloadTransfer $guiTableDataResponsePayloadTransfer */
            $guiTableDataResponsePayloadTransfer = $guiTableRowDataResponseTransfer->requirePayload()->getPayload();

            /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
            $itemTransfer = $guiTableDataResponsePayloadTransfer->requireItem()->getItem();
            $productOfferReference = $itemTransfer->getProductOfferReference();

            if (!$productOfferReference) {
                continue;
            }

            $productOfferReferences[] = $productOfferReference;

            $responseData[static::COL_KEY_PRODUCT_OFFER_REFERENCE] = $productOfferReference;

            $guiTableRowDataResponseTransfer->setResponseData($responseData);
        }

        if (!$productOfferReferences) {
            return $guiTableDataResponseTransfer;
        }

        $productOfferCollectionTransfer = $this->productOfferFacade->get(
            (new ProductOfferCriteriaTransfer())->setProductOfferReferences($productOfferReferences),
        );
        $merchantSkus = $this->getMerchantSkusIndexedByProductOfferReferences($productOfferCollectionTransfer);

        foreach ($guiTableDataResponseTransfer->getRows() as $guiTableRowDataResponseTransfer) {
            $responseData = $guiTableRowDataResponseTransfer->getResponseData();

            if (
                !isset($responseData[static::COL_KEY_PRODUCT_OFFER_REFERENCE])
                || !array_key_exists($responseData[static::COL_KEY_PRODUCT_OFFER_REFERENCE], $merchantSkus)
            ) {
                continue;
            }

            $responseData[static::COL_KEY_MERCHANT_SKU] = $merchantSkus[$responseData[static::COL_KEY_PRODUCT_OFFER_REFERENCE]];

            $guiTableRowDataResponseTransfer->setResponseData($responseData);
        }

        return $guiTableDataResponseTransfer;
    }

    /**
     * @phpstan-return array<string, string>
     *
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return array<string>
     */
    protected function getMerchantSkusIndexedByProductOfferReferences(
        ProductOfferCollectionTransfer $productOfferCollectionTransfer
    ): array {
        $merchantSkus = [];
        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $productOfferReference = $productOfferTransfer->getProductOfferReferenceOrFail();
            $merchantSku = $productOfferTransfer->getMerchantSku();

            if (!$merchantSku) {
                continue;
            }

            $merchantSkus[$productOfferReference] = $merchantSku;
        }

        return $merchantSkus;
    }
}
