<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\Propel;

use ArrayObject;
use Generated\Shared\Transfer\ProductTableDataTransfer;
use Generated\Shared\Transfer\ProductTableRowDataTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductValidity\Persistence\Map\SpyProductValidityTableMap;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface;

class ProductTableDataMapper
{
    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\ProductTable::COL_KEY_SKU
     */
    protected const COL_KEY_SKU = 'sku';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\ProductTable::COL_KEY_NAME
     */
    protected const COL_KEY_NAME = 'name';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\ProductTable::COL_KEY_STATUS
     */
    protected const COL_KEY_STATUS = 'status';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\ProductTable::COL_KEY_IMAGE
     */
    protected const COL_KEY_IMAGE = 'image';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\ProductTable::COL_KEY_OFFERS
     */
    protected const COL_KEY_OFFERS = 'offers';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\ProductTable::COL_KEY_STORES
     */
    protected const COL_KEY_STORES = 'stores';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\ProductTable::COL_KEY_VALID_FROM
     */
    protected const COL_KEY_VALID_FROM = 'validFrom';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\ProductTable::COL_KEY_VALID_TO
     */
    protected const COL_KEY_VALID_TO = 'validTo';

    public const PRODUCT_DATA_COLUMN_MAP = [
        self::COL_KEY_SKU => SpyProductTableMap::COL_SKU,
        self::COL_KEY_NAME => SpyProductLocalizedAttributesTableMap::COL_NAME,
        self::COL_KEY_STATUS => SpyProductTableMap::COL_IS_ACTIVE,
        self::COL_KEY_IMAGE => ProductTableRowDataTransfer::IMAGE,
        self::COL_KEY_OFFERS => ProductTableRowDataTransfer::OFFERS_COUNT,
        self::COL_KEY_STORES => ProductTableRowDataTransfer::STORES,
        self::COL_KEY_VALID_FROM => SpyProductValidityTableMap::COL_VALID_FROM,
        self::COL_KEY_VALID_TO => SpyProductValidityTableMap::COL_VALID_TO,
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
     * @param array $productTableDataArray
     * @param \Generated\Shared\Transfer\ProductTableDataTransfer $productTableDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductTableDataTransfer
     */
    public function mapProductTableDataArrayToTableDataTransfer(
        array $productTableDataArray,
        ProductTableDataTransfer $productTableDataTransfer
    ): ProductTableDataTransfer {
        $rowsData = [];

        foreach ($productTableDataArray as $productTableRowDataArray) {
            $productConcreteAttributes = $this->utilEncodingService->decodeJson(
                $productTableRowDataArray[ProductTableRowDataTransfer::PRODUCT_CONCRETE_ATTRIBUTES] ?? null,
                true
            );
            $productAbstractAttributes = $this->utilEncodingService->decodeJson(
                $productTableRowDataArray[ProductTableRowDataTransfer::PRODUCT_ABSTRACT_ATTRIBUTES] ?? null,
                true
            );
            $productConcreteLocalizedAttributes = $this->utilEncodingService->decodeJson(
                $productTableRowDataArray[ProductTableRowDataTransfer::PRODUCT_CONCRETE_LOCALIZED_ATTRIBUTES] ?? null,
                true
            );
            $productAbstractLocalizedAttributes = $this->utilEncodingService->decodeJson(
                $productTableRowDataArray[ProductTableRowDataTransfer::PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES] ?? null,
                true
            );
            $productTableRowDataTransfer = (new ProductTableRowDataTransfer())->fromArray($productTableRowDataArray, true);
            $productTableRowDataTransfer->setProductConcreteAttributes(is_array($productConcreteAttributes) ? $productConcreteAttributes : []);
            $productTableRowDataTransfer->setProductAbstractAttributes(is_array($productAbstractAttributes) ? $productAbstractAttributes : []);
            $productTableRowDataTransfer->setProductConcreteLocalizedAttributes(
                is_array($productConcreteLocalizedAttributes) ? $productConcreteLocalizedAttributes : []
            );
            $productTableRowDataTransfer->setProductAbstractLocalizedAttributes(
                is_array($productAbstractLocalizedAttributes) ? $productAbstractLocalizedAttributes : []
            );
            $rowsData[] = $productTableRowDataTransfer;
        }

        $productTableDataTransfer->setRows(new ArrayObject($rowsData));

        return $productTableDataTransfer;
    }
}
