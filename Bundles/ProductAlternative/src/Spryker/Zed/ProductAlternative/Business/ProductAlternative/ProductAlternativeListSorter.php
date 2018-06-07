<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternative;

use ArrayObject;
use Generated\Shared\Transfer\ProductAlternativeListItemTransfer;
use Generated\Shared\Transfer\ProductAlternativeListTransfer;

class ProductAlternativeListSorter implements ProductAlternativeListSorterInterface
{
    protected const TYPE_PRODUCT_ABSTRACT = 'Abstract';
    protected const TYPE_PRODUCT_CONCRETE = 'Concrete';

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeListTransfer $productAlternativeListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListTransfer
     */
    public function sortProductAlternativeList(ProductAlternativeListTransfer $productAlternativeListTransfer): ProductAlternativeListTransfer
    {
        $productAlternativeListItems = $productAlternativeListTransfer
            ->getProductAlternatives()
            ->getArrayCopy();

        $filteredProductAlternatives = $this
            ->filterProductAlternatives($productAlternativeListItems);

        $sortedProductAlternativeListItems = array_merge(
            $this->sortProductAlternatives($filteredProductAlternatives[static::TYPE_PRODUCT_ABSTRACT]),
            $this->sortProductAlternatives($filteredProductAlternatives[static::TYPE_PRODUCT_CONCRETE])
        );

        return $productAlternativeListTransfer->setProductAlternatives(
            new ArrayObject($sortedProductAlternativeListItems)
        );
    }

    /**
     * @param array $productAlternativeListItems
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer[]
     */
    protected function sortProductAlternatives(array &$productAlternativeListItems): array
    {
        usort(
            $productAlternativeListItems,
            function (ProductAlternativeListItemTransfer $a, ProductAlternativeListItemTransfer $b) {
                return $a->getIdProductAlternative() - $b->getIdProductAlternative();
            }
        );

        return $productAlternativeListItems;
    }

    /**
     * @param array $productAlternativeListItems
     *
     * @return array
     */
    protected function filterProductAlternatives(array &$productAlternativeListItems): array
    {
        $filteredProductAlternatives = [
            static::TYPE_PRODUCT_ABSTRACT => [],
            static::TYPE_PRODUCT_CONCRETE => [],
        ];

        /** @var \Generated\Shared\Transfer\ProductAlternativeListItemTransfer $productAlternativeListItem */
        foreach ($productAlternativeListItems as $productAlternativeListItem) {
            $productAlternativeType = $productAlternativeListItem->getType();

            if ($productAlternativeType === static::TYPE_PRODUCT_ABSTRACT) {
                $filteredProductAlternatives[static::TYPE_PRODUCT_ABSTRACT][] = $productAlternativeListItem;
            }

            if ($productAlternativeType === static::TYPE_PRODUCT_CONCRETE) {
                $filteredProductAlternatives[static::TYPE_PRODUCT_CONCRETE][] = $productAlternativeListItem;
            }
        }

        return $filteredProductAlternatives;
    }
}
