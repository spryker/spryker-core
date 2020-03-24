<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Persistence\Propel;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferTableDataTransfer;
use Generated\Shared\Transfer\ProductOfferTableRowDataTransfer;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilEncodingServiceInterface;

class ProductOfferTableDataMapper
{
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
