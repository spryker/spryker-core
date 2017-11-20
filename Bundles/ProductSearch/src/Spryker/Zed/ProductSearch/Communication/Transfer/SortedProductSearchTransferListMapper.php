<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Transfer;

use Generated\Shared\Transfer\ProductSearchAttributeTransfer;

class SortedProductSearchTransferListMapper implements SortedProductSearchTransferListMapperInterface
{
    /**
     * @param array $filterList
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer[]
     */
    public function createList(array $filterList)
    {
        $productSearchAttributeList = [];

        $position = 1;
        foreach ($filterList as $filter) {
            $productSearchAttributeTransfer = new ProductSearchAttributeTransfer();
            $productSearchAttributeTransfer
                ->setIdProductSearchAttribute($filter[ProductSearchAttributeTransfer::ID_PRODUCT_SEARCH_ATTRIBUTE])
                ->setPosition($position++);

            $productSearchAttributeList[] = $productSearchAttributeTransfer;
        }

        return $productSearchAttributeList;
    }
}
