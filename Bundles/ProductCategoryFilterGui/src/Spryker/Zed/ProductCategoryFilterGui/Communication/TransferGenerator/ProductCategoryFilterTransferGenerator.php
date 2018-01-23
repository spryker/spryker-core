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
    const IS_ACTIVE_FIELD = 'isActive';
    const LABEL_FIELD = 'label';

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

        $data = call_user_func_array(
            'array_merge',
            $this->utilEncodingService->decodeJson($jsonData,true)
        );

        foreach ($data as $key => $value) {
            $productCategoryFilterItemTransfer = new ProductCategoryFilterItemTransfer();
            $productCategoryFilterItemTransfer->setIsActive($value[static::IS_ACTIVE_FIELD]);
            $productCategoryFilterItemTransfer->setLabel($value[static::LABEL_FIELD]);
            $productCategoryFilterItemTransfer->setKey($key);

            $productCategoryFilterTransfer->addProductCategoryFilterItem($productCategoryFilterItemTransfer);
        }

        return $productCategoryFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function generateTransferWithJsonFromTransfer(ProductCategoryFilterTransfer $productCategoryFilterTransfer)
    {
        $finalJson = [];

        $productCategoryFilterItemTransfers = $productCategoryFilterTransfer->getFilters();
        foreach($productCategoryFilterItemTransfers as $productCategoryFilterItemTransfer) {
            $finalJson[] = [
                $productCategoryFilterItemTransfer->getKey() => [
                    static::IS_ACTIVE_FIELD => $productCategoryFilterItemTransfer->getIsActive(),
                    static::LABEL_FIELD => $productCategoryFilterItemTransfer->getLabel(),
                    ]
            ];
        }

        $productCategoryFilterTransfer->setFilterData($this->utilEncodingService->encodeJson($finalJson,true));

        return $productCategoryFilterTransfer;
    }
}
