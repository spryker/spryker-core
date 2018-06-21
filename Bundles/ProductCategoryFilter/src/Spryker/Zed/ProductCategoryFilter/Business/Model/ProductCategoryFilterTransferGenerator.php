<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

use Generated\Shared\Transfer\ProductCategoryFilterItemTransfer;
use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Zed\ProductCategoryFilter\Dependency\Service\ProductCategoryFilterToUtilEncodingServiceInterface;

class ProductCategoryFilterTransferGenerator implements ProductCategoryFilterTransferGeneratorInterface
{
    /**
     * @var \Spryker\Zed\ProductCategoryFilter\Dependency\Service\ProductCategoryFilterToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductCategoryFilter\Dependency\Service\ProductCategoryFilterToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(ProductCategoryFilterToUtilEncodingServiceInterface $utilEncodingService)
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
        $productCategoryFilterTransfer->setIdProductCategoryFilter($idProductCategoryFilter);
        $productCategoryFilterTransfer->setFkCategory($idCategory);

        if (empty($jsonData)) {
            return $productCategoryFilterTransfer;
        }

        $productCategoryFilterTransfer->fromArray($this->utilEncodingService->decodeJson($jsonData, true), true);

        return $productCategoryFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function generateTransferWithJsonFromTransfer(ProductCategoryFilterTransfer $productCategoryFilterTransfer)
    {
        $productCategoryFilterItemArray = [
            ProductCategoryFilterTransfer::FILTERS => [],
        ];

        $productCategoryFilterItemTransfers = $productCategoryFilterTransfer->getFilters();
        foreach ($productCategoryFilterItemTransfers as $productCategoryFilterItemTransfer) {
            $productCategoryFilterItemArray[ProductCategoryFilterTransfer::FILTERS][] = [
                ProductCategoryFilterItemTransfer::IS_ACTIVE => $productCategoryFilterItemTransfer->getIsActive(),
                ProductCategoryFilterItemTransfer::LABEL => $productCategoryFilterItemTransfer->getLabel(),
                ProductCategoryFilterItemTransfer::KEY => $productCategoryFilterItemTransfer->getKey(),
            ];
        }

        $productCategoryFilterTransfer->setFilterData($this->utilEncodingService->encodeJson($productCategoryFilterItemArray, true));

        return $productCategoryFilterTransfer;
    }
}
