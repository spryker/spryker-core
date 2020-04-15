<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\Propel;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferTableDataTransfer;
use Generated\Shared\Transfer\ProductOfferTableRowDataTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOfferStock\Persistence\Map\SpyProductOfferStockTableMap;
use Orm\Zed\ProductOfferValidity\Persistence\Map\SpyProductOfferValidityTableMap;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface;

class ProductOfferTableDataMapper
{
    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductTable::COL_KEY_OFFER_REFERENCE
     */
    protected const COL_KEY_OFFER_REFERENCE = 'offerReference';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductTable::COL_KEY_MERCHANT_SKU
     */
    protected const COL_KEY_MERCHANT_SKU = 'merchantSku';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductTable::COL_KEY_CONCRETE_SKU
     */
    protected const COL_KEY_CONCRETE_SKU = 'concreteSku';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductTable::COL_KEY_IMAGE
     */
    protected const COL_KEY_IMAGE = 'image';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductTable::COL_KEY_PRODUCT_NAME
     */
    protected const COL_KEY_PRODUCT_NAME = 'productName';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductTable::COL_KEY_STORES
     */
    protected const COL_KEY_STORES = 'stores';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductTable::COL_KEY_STOCK
     */
    protected const COL_KEY_STOCK = 'stock';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductTable::COL_KEY_VISIBILITY
     */
    protected const COL_KEY_VISIBILITY = 'visibility';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductTable::COL_KEY_VALID_FROM
     */
    protected const COL_KEY_VALID_FROM = 'validFrom';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductTable::COL_KEY_VALID_TO
     */
    protected const COL_KEY_VALID_TO = 'validTo';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductTable::COL_KEY_APPROVAL_STATUS
     */
    protected const COL_KEY_APPROVAL_STATUS = 'approvalStatus';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductTable::COL_KEY_CREATED_AT
     */
    protected const COL_KEY_CREATED_AT = 'createdAt';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductTable::COL_KEY_UPDATED_AT
     */
    protected const COL_KEY_UPDATED_AT = 'updatedAt';

    public const PRODUCT_OFFER_DATA_COLUMN_MAP = [
        self::COL_KEY_OFFER_REFERENCE => SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE,
        self::COL_KEY_MERCHANT_SKU => SpyProductOfferTableMap::COL_MERCHANT_SKU,
        self::COL_KEY_CONCRETE_SKU => SpyProductOfferTableMap::COL_CONCRETE_SKU,
        self::COL_KEY_IMAGE => ProductOfferTableRowDataTransfer::IMAGE,
        self::COL_KEY_PRODUCT_NAME => ProductOfferTableRowDataTransfer::PRODUCT_CONCRETE_NAME,
        self::COL_KEY_STORES => ProductOfferTableRowDataTransfer::STORES,
        self::COL_KEY_STOCK => SpyProductOfferStockTableMap::COL_QUANTITY,
        self::COL_KEY_VISIBILITY => SpyProductOfferTableMap::COL_IS_ACTIVE,
        self::COL_KEY_VALID_FROM => SpyProductOfferValidityTableMap::COL_VALID_FROM,
        self::COL_KEY_VALID_TO => SpyProductOfferValidityTableMap::COL_VALID_TO,
        self::COL_KEY_APPROVAL_STATUS => SpyProductOfferTableMap::COL_APPROVAL_STATUS,
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
     * @param array $productTableDataArray
     * @param \Generated\Shared\Transfer\ProductOfferTableDataTransfer $productOfferTableDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTableDataTransfer
     */
    public function mapProductOfferTableDataArrayToTableDataTransfer(
        array $productTableDataArray,
        ProductOfferTableDataTransfer $productOfferTableDataTransfer
    ): ProductOfferTableDataTransfer {
        $rowsData = [];

        foreach ($productTableDataArray as $productTableRowDataArray) {
            $productConcreteAttributes = $this->utilEncodingService->decodeJson(
                $productTableRowDataArray[ProductOfferTableRowDataTransfer::PRODUCT_CONCRETE_ATTRIBUTES] ?? null,
                true
            );
            $productAbstractAttributes = $this->utilEncodingService->decodeJson(
                $productTableRowDataArray[ProductOfferTableRowDataTransfer::PRODUCT_ABSTRACT_ATTRIBUTES] ?? null,
                true
            );
            $productTableRowDataTransfer = (new ProductOfferTableRowDataTransfer())->fromArray($productTableRowDataArray, true);
            $productTableRowDataTransfer->setProductConcreteAttributes(is_array($productConcreteAttributes) ? $productConcreteAttributes : []);
            $productTableRowDataTransfer->setProductAbstractAttributes(is_array($productAbstractAttributes) ? $productAbstractAttributes : []);
            $rowsData[] = $productTableRowDataTransfer;
        }

        $productOfferTableDataTransfer->setRows(new ArrayObject($rowsData));

        return $productOfferTableDataTransfer;
    }
}
