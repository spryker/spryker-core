<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\TransferGenerator;

use Generated\Shared\Transfer\ProductCategoryFilterItemTransfer;
use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\Service\ProductCategoryFilterGuiToUtilEncodingServiceInterface;

class ProductCategoryFilterTransferGenerator implements ProductCategoryFilterTransferGeneratorInterface
{
    /**
     * @var \Spryker\Zed\ProductCategoryFilterGui\Dependency\Service\ProductCategoryFilterGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductCategoryFilterGui\Dependency\Service\ProductCategoryFilterGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(ProductCategoryFilterGuiToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param int $idProductCategoryFilter
     * @param int $idCategory
     * @param string $jsonData
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function generateTransferFromJson($idProductCategoryFilter, $idCategory, $jsonData)
    {
        $productCategoryFilterTransfer = new ProductCategoryFilterTransfer();
        $productCategoryFilterTransfer->setFkCategory($idCategory);
        $productCategoryFilterTransfer->setIdProductCategoryFilter($idProductCategoryFilter);
        $productCategoryFilterTransfer->fromArray($this->utilEncodingService->decodeJson($jsonData, true), true);
        $productCategoryFilterTransfer->setFilterData($jsonData);
        $productCategoryFilterTransfer->setFilterDataArray($this->utilEncodingService->decodeJson($jsonData, true));

        return $productCategoryFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function generateTransferWithJsonFromTransfer(ProductCategoryFilterTransfer $productCategoryFilterTransfer)
    {
        $productCategoryFilterItemArray = [];

        $productCategoryFilterItemTransfers = $productCategoryFilterTransfer->getFilters();
        foreach ($productCategoryFilterItemTransfers as $productCategoryFilterItemTransfer) {
            $productCategoryFilterItemArray[] = [
                ProductCategoryFilterItemTransfer::IS_ACTIVE => $productCategoryFilterItemTransfer->getIsActive(),
                ProductCategoryFilterItemTransfer::LABEL => $productCategoryFilterItemTransfer->getLabel(),
                ProductCategoryFilterItemTransfer::KEY => $productCategoryFilterItemTransfer->getKey(),
            ];
        }

        $productCategoryFilterTransfer->setFilterData($this->utilEncodingService->encodeJson($productCategoryFilterItemArray, true));
        $productCategoryFilterTransfer->setFilterDataArray($productCategoryFilterItemArray);

        return $productCategoryFilterTransfer;
    }
}
