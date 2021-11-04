<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductAttributeInterface;

class ProductAttributeReader implements ProductAttributeReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductAttributeInterface
     */
    protected $productAttributeFacade;

    /**
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductAttributeInterface $productAttributeFacade
     */
    public function __construct(ProductManagementToProductAttributeInterface $productAttributeFacade)
    {
        $this->productAttributeFacade = $productAttributeFacade;
    }

    /**
     * @return array<\Generated\Shared\Transfer\ProductManagementAttributeTransfer>
     */
    public function getProductSuperAttributesIndexedByAttributeKey(): array
    {
        $productManagementAttributeFilterTransfer = new ProductManagementAttributeFilterTransfer();
        $productManagementAttributeFilterTransfer->setOnlySuperAttributes(true);

        $productManagementAttributeCollectionTransfer = $this->productAttributeFacade
            ->getProductManagementAttributes($productManagementAttributeFilterTransfer);

        return $this->indexProductManagementAttributesByAttributeKey(
            $productManagementAttributeCollectionTransfer->getProductManagementAttributes(),
        );
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductManagementAttributeTransfer> $productManagementAttributeTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductManagementAttributeTransfer>
     */
    protected function indexProductManagementAttributesByAttributeKey(ArrayObject $productManagementAttributeTransfers): array
    {
        $mappedProductManagementAttributeTransfers = [];
        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            $mappedProductManagementAttributeTransfers[$productManagementAttributeTransfer->getKey()] = $productManagementAttributeTransfer;
        }

        return $mappedProductManagementAttributeTransfers;
    }
}
