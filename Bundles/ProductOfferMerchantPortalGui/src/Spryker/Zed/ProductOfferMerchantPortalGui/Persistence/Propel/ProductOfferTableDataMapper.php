<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\Propel;

use ArrayObject;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ProductOfferValidityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOfferStock\Persistence\Map\SpyProductOfferStockTableMap;
use Orm\Zed\ProductOfferValidity\Persistence\Map\SpyProductOfferValidityTableMap;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface;

class ProductOfferTableDataMapper
{
    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductOfferGuiTableConfigurationProvider::COL_KEY_OFFER_REFERENCE
     *
     * @var string
     */
    protected const COL_KEY_OFFER_REFERENCE = 'offerReference';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductOfferGuiTableConfigurationProvider::COL_KEY_MERCHANT_SKU
     *
     * @var string
     */
    protected const COL_KEY_MERCHANT_SKU = 'merchantSku';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductOfferGuiTableConfigurationProvider::COL_KEY_CONCRETE_SKU
     *
     * @var string
     */
    protected const COL_KEY_CONCRETE_SKU = 'concreteSku';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductOfferGuiTableConfigurationProvider::COL_KEY_IMAGE
     *
     * @var string
     */
    protected const COL_KEY_IMAGE = 'image';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductOfferGuiTableConfigurationProvider::COL_KEY_PRODUCT_NAME
     *
     * @var string
     */
    protected const COL_KEY_PRODUCT_NAME = 'productName';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductOfferGuiTableConfigurationProvider::COL_KEY_STORES
     *
     * @var string
     */
    protected const COL_KEY_STORES = 'stores';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductOfferGuiTableConfigurationProvider::COL_KEY_STOCK
     *
     * @var string
     */
    protected const COL_KEY_STOCK = 'stock';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductOfferGuiTableConfigurationProvider::COL_KEY_STATUS
     *
     * @var string
     */
    protected const COL_KEY_STATUS = 'status';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductOfferGuiTableConfigurationProvider::COL_KEY_APPROVAL_STATUS
     *
     * @var string
     */
    protected const COL_KEY_APPROVAL_STATUS = 'approvalStatus';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductOfferGuiTableConfigurationProvider::COL_KEY_VALID_FROM
     *
     * @var string
     */
    protected const COL_KEY_VALID_FROM = 'validFrom';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductOfferGuiTableConfigurationProvider::COL_KEY_VALID_TO
     *
     * @var string
     */
    protected const COL_KEY_VALID_TO = 'validTo';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductOfferGuiTableConfigurationProvider::COL_KEY_CREATED_AT
     *
     * @var string
     */
    protected const COL_KEY_CREATED_AT = 'createdAt';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductOfferGuiTableConfigurationProvider::COL_KEY_UPDATED_AT
     *
     * @var string
     */
    protected const COL_KEY_UPDATED_AT = 'updatedAt';

    /**
     * @var array<string, string>
     */
    public const PRODUCT_OFFER_DATA_COLUMN_MAP = [
        self::COL_KEY_OFFER_REFERENCE => SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE,
        self::COL_KEY_MERCHANT_SKU => SpyProductOfferTableMap::COL_MERCHANT_SKU,
        self::COL_KEY_CONCRETE_SKU => SpyProductOfferTableMap::COL_CONCRETE_SKU,
        self::COL_KEY_IMAGE => ProductImageTransfer::EXTERNAL_URL_SMALL,
        self::COL_KEY_PRODUCT_NAME => LocalizedAttributesTransfer::NAME,
        self::COL_KEY_STORES => ProductOfferTransfer::STORES,
        self::COL_KEY_STOCK => SpyProductOfferStockTableMap::COL_QUANTITY,
        self::COL_KEY_STATUS => SpyProductOfferTableMap::COL_IS_ACTIVE,
        self::COL_KEY_APPROVAL_STATUS => SpyProductOfferTableMap::COL_APPROVAL_STATUS,
        self::COL_KEY_VALID_FROM => SpyProductOfferValidityTableMap::COL_VALID_FROM,
        self::COL_KEY_VALID_TO => SpyProductOfferValidityTableMap::COL_VALID_TO,
        self::COL_KEY_CREATED_AT => SpyProductOfferTableMap::COL_CREATED_AT,
        self::COL_KEY_UPDATED_AT => SpyProductOfferTableMap::COL_UPDATED_AT,
    ];

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param array<mixed> $productOfferTableDataArray
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function mapProductOfferTableDataArrayToProductOfferCollectionTransfer(
        array $productOfferTableDataArray,
        ProductOfferCollectionTransfer $productOfferCollectionTransfer
    ): ProductOfferCollectionTransfer {
        $productOffers = [];

        foreach ($productOfferTableDataArray as $productOfferTableRowDataArray) {
            $productOfferTableRowDataArray = $this->prepareProductOfferStoresTableData($productOfferTableRowDataArray);
            $productOfferTableRowDataArray = $this->prepareProductOfferAttributesTableData($productOfferTableRowDataArray);

            $productOfferTransfer = (new ProductOfferTransfer())->fromArray($productOfferTableRowDataArray, true);
            $productOfferTransfer->setStores($productOfferTableRowDataArray[ProductOfferTransfer::STORES]);
            $productOfferTransfer->setProductAttributes($productOfferTableRowDataArray[ProductOfferTransfer::PRODUCT_ATTRIBUTES]);
            $productOfferTransfer->setProductLocalizedAttributes($productOfferTableRowDataArray[ProductOfferTransfer::PRODUCT_LOCALIZED_ATTRIBUTES]);
            $productOfferTransfer = $this->mapImageToProductOffer($productOfferTableRowDataArray, $productOfferTransfer);
            $productOfferTransfer = $this->mapStockToProductOffer($productOfferTableRowDataArray, $productOfferTransfer);
            $productOfferTransfer = $this->mapValidityToProductOffer($productOfferTableRowDataArray, $productOfferTransfer);

            $productOffers[] = $productOfferTransfer;
        }

        $productOfferCollectionTransfer->setProductOffers(new ArrayObject($productOffers));

        return $productOfferCollectionTransfer;
    }

    /**
     * @param array<mixed> $productOfferTableRowDataArray
     *
     * @return array<mixed>
     */
    protected function prepareProductOfferStoresTableData(array $productOfferTableRowDataArray): array
    {
        $stores = array_filter(
            explode(',', $productOfferTableRowDataArray[ProductOfferTransfer::STORES]),
        );

        $storeTransfers = array_map(function (string $storeName): StoreTransfer {
            return (new StoreTransfer())->setName($storeName);
        }, $stores);

        $productOfferTableRowDataArray[ProductOfferTransfer::STORES] = new ArrayObject($storeTransfers);

        return $productOfferTableRowDataArray;
    }

    /**
     * @param array<mixed> $productOfferTableRowDataArray
     *
     * @return array<mixed>
     */
    protected function prepareProductOfferAttributesTableData(array $productOfferTableRowDataArray): array
    {
        $productConcreteAttributes = $this->utilEncodingService->decodeJson(
            $productOfferTableRowDataArray[ProductOfferTransfer::PRODUCT_ATTRIBUTES] ?? null,
            true,
        );
        $productConcreteAttributes = is_array($productConcreteAttributes) ? $productConcreteAttributes : [];

        $productConcreteLocalizedAttributes = $this->utilEncodingService->decodeJson(
            $productOfferTableRowDataArray[ProductOfferTransfer::PRODUCT_LOCALIZED_ATTRIBUTES] ?? null,
            true,
        );
        $productConcreteLocalizedAttributes = is_array($productConcreteLocalizedAttributes) ? $productConcreteLocalizedAttributes : [];

        $localizedAttributesTransfer = (new LocalizedAttributesTransfer())
            ->setAttributes($productConcreteLocalizedAttributes)
            ->setName($productOfferTableRowDataArray[LocalizedAttributesTransfer::NAME]);

        $productOfferTableRowDataArray[ProductOfferTransfer::PRODUCT_ATTRIBUTES] = $productConcreteAttributes;
        $productOfferTableRowDataArray[ProductOfferTransfer::PRODUCT_LOCALIZED_ATTRIBUTES] = new ArrayObject([$localizedAttributesTransfer]);

        return $productOfferTableRowDataArray;
    }

    /**
     * @param array<mixed> $productOfferTableRowDataArray
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function mapImageToProductOffer(
        array $productOfferTableRowDataArray,
        ProductOfferTransfer $productOfferTransfer
    ): ProductOfferTransfer {
        $productImageTransfer = (new ProductImageTransfer())
            ->setExternalUrlSmall($productOfferTableRowDataArray[ProductImageTransfer::EXTERNAL_URL_SMALL]);

        $productOfferTransfer->setProductImages(new ArrayObject([$productImageTransfer]));

        return $productOfferTransfer;
    }

    /**
     * @param array<mixed> $productOfferTableRowDataArray
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function mapStockToProductOffer(
        array $productOfferTableRowDataArray,
        ProductOfferTransfer $productOfferTransfer
    ): ProductOfferTransfer {
        $productOfferStockTransfer = (new ProductOfferStockTransfer())
            ->setQuantity($productOfferTableRowDataArray[ProductOfferStockTransfer::QUANTITY]);

        $productOfferTransfer->addProductOfferStock($productOfferStockTransfer);

        return $productOfferTransfer;
    }

    /**
     * @param array<mixed> $productOfferTableRowDataArray
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function mapValidityToProductOffer(
        array $productOfferTableRowDataArray,
        ProductOfferTransfer $productOfferTransfer
    ): ProductOfferTransfer {
        $productOfferValidityTransfer = (new ProductOfferValidityTransfer())
            ->setValidFrom($productOfferTableRowDataArray[ProductOfferValidityTransfer::VALID_FROM])
            ->setValidTo($productOfferTableRowDataArray[ProductOfferValidityTransfer::VALID_TO]);

        $productOfferTransfer->setProductOfferValidity($productOfferValidityTransfer);

        return $productOfferTransfer;
    }
}
