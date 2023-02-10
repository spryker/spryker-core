<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\Propel;

use ArrayObject;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductValidity\Persistence\Map\SpyProductValidityTableMap;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface;

class ProductTableDataMapper
{
    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider::COL_KEY_SKU
     *
     * @var string
     */
    protected const COL_KEY_SKU = 'sku';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider::COL_KEY_NAME
     *
     * @var string
     */
    protected const COL_KEY_NAME = 'name';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider::COL_KEY_STATUS
     *
     * @var string
     */
    protected const COL_KEY_STATUS = 'status';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider::COL_KEY_IMAGE
     *
     * @var string
     */
    protected const COL_KEY_IMAGE = 'image';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider::COL_KEY_OFFERS
     *
     * @var string
     */
    protected const COL_KEY_OFFERS = 'offers';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider::COL_KEY_STORES
     *
     * @var string
     */
    protected const COL_KEY_STORES = 'stores';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider::COL_KEY_VALID_FROM
     *
     * @var string
     */
    protected const COL_KEY_VALID_FROM = 'validFrom';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider::COL_KEY_VALID_TO
     *
     * @var string
     */
    protected const COL_KEY_VALID_TO = 'validTo';

    /**
     * @var array<string, string>
     */
    public const PRODUCT_DATA_COLUMN_MAP = [
        self::COL_KEY_SKU => SpyProductTableMap::COL_SKU,
        self::COL_KEY_NAME => SpyProductLocalizedAttributesTableMap::COL_NAME,
        self::COL_KEY_STATUS => SpyProductTableMap::COL_IS_ACTIVE,
        self::COL_KEY_IMAGE => ProductImageTransfer::EXTERNAL_URL_SMALL,
        self::COL_KEY_OFFERS => ProductConcreteTransfer::NUMBER_OF_OFFERS,
        self::COL_KEY_STORES => ProductConcreteTransfer::STORES,
        self::COL_KEY_VALID_FROM => SpyProductValidityTableMap::COL_VALID_FROM,
        self::COL_KEY_VALID_TO => SpyProductValidityTableMap::COL_VALID_TO,
    ];

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface
     */
    protected ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param array<mixed> $productTableDataArray
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function mapProductTableDataArrayToProductConcreteCollectionTransfer(
        array $productTableDataArray,
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): ProductConcreteCollectionTransfer {
        $products = [];

        foreach ($productTableDataArray as $productTableRowDataArray) {
            $productTableRowDataArray = $this->prepareProductStoresTableData($productTableRowDataArray);
            $productTableRowDataArray = $this->prepareProductAttributesTableData($productTableRowDataArray);

            $productConcreteTransfer = (new ProductConcreteTransfer())->fromArray($productTableRowDataArray, true);
            $productConcreteTransfer->setStores($productTableRowDataArray[ProductConcreteTransfer::STORES]);
            $productConcreteTransfer->setAttributes($productTableRowDataArray[ProductConcreteTransfer::ATTRIBUTES]);
            $productConcreteTransfer->setLocalizedAttributes($productTableRowDataArray[ProductConcreteTransfer::LOCALIZED_ATTRIBUTES]);
            $productConcreteTransfer = $this->mapImageToProductConcrete($productTableRowDataArray, $productConcreteTransfer);

            $products[] = $productConcreteTransfer;
        }

        $productConcreteCollectionTransfer->setProducts(new ArrayObject($products));

        return $productConcreteCollectionTransfer;
    }

    /**
     * @param array<mixed> $productTableRowDataArray
     *
     * @return array<mixed>
     */
    protected function prepareProductStoresTableData(array $productTableRowDataArray): array
    {
        $stores = array_filter(
            explode(',', $productTableRowDataArray[ProductConcreteTransfer::STORES]),
        );

        $storeTransfers = array_map(function (string $storeName): StoreTransfer {
            return (new StoreTransfer())->setName($storeName);
        }, $stores);

        $productTableRowDataArray[ProductConcreteTransfer::STORES] = new ArrayObject($storeTransfers);

        return $productTableRowDataArray;
    }

    /**
     * @param array<mixed> $productTableRowDataArray
     *
     * @return array<mixed>
     */
    protected function prepareProductAttributesTableData(array $productTableRowDataArray): array
    {
        $productConcreteAttributes = $this->utilEncodingService->decodeJson(
            $productTableRowDataArray[ProductConcreteTransfer::ATTRIBUTES] ?? '',
            true,
        );
        $productConcreteAttributes = is_array($productConcreteAttributes) ? $productConcreteAttributes : [];

        $productConcreteLocalizedAttributes = $this->utilEncodingService->decodeJson(
            $productTableRowDataArray[ProductConcreteTransfer::LOCALIZED_ATTRIBUTES] ?? '',
            true,
        );
        $productConcreteLocalizedAttributes = is_array($productConcreteLocalizedAttributes) ? $productConcreteLocalizedAttributes : [];

        $localizedAttributesTransfer = (new LocalizedAttributesTransfer())
            ->setAttributes($productConcreteLocalizedAttributes)
            ->setName($productTableRowDataArray[LocalizedAttributesTransfer::NAME]);

        $productTableRowDataArray[ProductConcreteTransfer::ATTRIBUTES] = $productConcreteAttributes;
        $productTableRowDataArray[ProductConcreteTransfer::LOCALIZED_ATTRIBUTES] = new ArrayObject([$localizedAttributesTransfer]);

        return $productTableRowDataArray;
    }

    /**
     * @param array<mixed> $productTableRowDataArray
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function mapImageToProductConcrete(
        array $productTableRowDataArray,
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer {
        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->addProductImage((new ProductImageTransfer())->setExternalUrlSmall($productTableRowDataArray[ProductImageTransfer::EXTERNAL_URL_SMALL]));

        $productConcreteTransfer->setImageSets(new ArrayObject([$productImageSetTransfer]));

        return $productConcreteTransfer;
    }
}
