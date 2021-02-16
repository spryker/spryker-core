<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryNodeAggregationTransfer;
use Propel\Runtime\Collection\Collection;

class CategoryNodeMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection $categoryNodeCollection
     * @param \Generated\Shared\Transfer\CategoryNodeAggregationTransfer[] $categoryNodeAggregationTransfers
     *
     * @return \Generated\Shared\Transfer\CategoryNodeAggregationTransfer[]
     */
    public function mapCategoryNodesToCategoryNodeAggregationTransfers(
        Collection $categoryNodeCollection,
        array $categoryNodeAggregationTransfers
    ): array {
        foreach ($categoryNodeCollection as $categoryNode) {
            $categoryNodeAggregationTransfers[] = (new CategoryNodeAggregationTransfer())->fromArray($categoryNode, true);
        }

        return $categoryNodeAggregationTransfers;
    }
}
