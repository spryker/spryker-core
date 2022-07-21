<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Validator;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductSearchFacadeInterface;

class ProductCategoryFilterValidator implements ProductCategoryFilterValidatorInterface
{
    /**
     * @var \Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductSearchFacadeInterface
     */
    protected ProductCategoryFilterGuiToProductSearchFacadeInterface $productSearchFacade;

    /**
     * @param \Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductSearchFacadeInterface $productSearchFacade
     */
    public function __construct(ProductCategoryFilterGuiToProductSearchFacadeInterface $productSearchFacade)
    {
        $this->productSearchFacade = $productSearchFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     * @param array<int, int|string> $searchFilterKeys
     *
     * @return bool
     */
    public function validate(ProductCategoryFilterTransfer $productCategoryFilterTransfer, array $searchFilterKeys): bool
    {
        $productCategoryFilterKeys = $this->extractFilterKeysFromProductCategoryFilter($productCategoryFilterTransfer);
        $existingFilterKeys = array_merge($searchFilterKeys, $this->productSearchFacade->getAllProductAttributeKeys());

        return array_diff($productCategoryFilterKeys, $existingFilterKeys) === [];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return array<string>
     */
    protected function extractFilterKeysFromProductCategoryFilter(ProductCategoryFilterTransfer $productCategoryFilterTransfer): array
    {
        $filterKeys = [];
        foreach ($productCategoryFilterTransfer->getFilters() as $productCategoryFilterItemTransfer) {
            $filterKeys[] = $productCategoryFilterItemTransfer->getKeyOrFail();
        }

        return $filterKeys;
    }
}
