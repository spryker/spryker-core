<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Persistence\Propel;

use ArrayObject;
use Generated\Shared\Transfer\ProductTableDataTransfer;
use Generated\Shared\Transfer\ProductTableRowDataTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductValidity\Persistence\Map\SpyProductValidityTableMap;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilEncodingServiceInterface;

class ProductTableDataMapper
{
    /**
     * @uses \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\ProductTable::COL_KEY_SKU
     */
    protected const COL_KEY_SKU = 'sku';

    /**
     * @uses \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\ProductTable::COL_KEY_NAME
     */
    protected const COL_KEY_NAME = 'name';

    /**
     * @uses \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\ProductTable::COL_KEY_STATUS
     */
    protected const COL_KEY_STATUS = 'status';

    /**
     * @uses \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\ProductTable::COL_KEY_VALID_FROM
     */
    protected const COL_KEY_VALID_FROM = 'validFrom';

    /**
     * @uses \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\ProductTable::COL_KEY_VALID_TO
     */
    protected const COL_KEY_VALID_TO = 'validTo';

    public const PRODUCT_DATA_COLUMN_MAP = [
        self::COL_KEY_SKU => SpyProductTableMap::COL_SKU,
        self::COL_KEY_NAME => SpyProductLocalizedAttributesTableMap::COL_NAME,
        self::COL_KEY_STATUS => SpyProductTableMap::COL_IS_ACTIVE,
        self::COL_KEY_VALID_FROM => SpyProductValidityTableMap::COL_VALID_FROM,
        self::COL_KEY_VALID_TO => SpyProductValidityTableMap::COL_VALID_TO,
    ];

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(ProductOfferGuiPageToUtilEncodingServiceInterface $utilEncodingService)
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
                $productTableRowDataArray[ProductTableRowDataTransfer::ATTRIBUTES] ?? null,
                true
            );
            $productTableRowDataTransfer = (new ProductTableRowDataTransfer())->fromArray($productTableRowDataArray, true);
            $productTableRowDataTransfer->setAttributes(is_array($productConcreteAttributes) ? $productConcreteAttributes : []);
            $rowsData[] = $productTableRowDataTransfer;
        }

        $productTableDataTransfer->setRows(new ArrayObject($rowsData));

        return $productTableDataTransfer;
    }
}
