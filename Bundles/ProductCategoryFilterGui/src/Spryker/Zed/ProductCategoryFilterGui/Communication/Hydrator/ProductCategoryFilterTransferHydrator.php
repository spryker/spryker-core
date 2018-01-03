<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Hydrator;

use Spryker\Zed\ProductCategoryFilterGui\Dependency\Service\ProductCategoryFilterGuiToUtilEncodingServiceInterface;

class ProductCategoryFilterTransferHydrator implements HydratorInterface
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
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     * @param string|array $data
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function hydrate($productCategoryFilterTransfer, $data)
    {
        if (is_string($data)) {
            $data = $this->utilEncodingService->decodeJson($data, true);
        }

        $formattedData = [];
        foreach ($data as $dataToHydrate) {
            foreach ($dataToHydrate as $key => $value) {
                $formattedData[$key] = $value;
            }
        }

        $productCategoryFilterTransfer->setFilterData($this->utilEncodingService->encodeJson($formattedData));
        $productCategoryFilterTransfer->setFilterDataArray($formattedData);

        return $productCategoryFilterTransfer;
    }
}
