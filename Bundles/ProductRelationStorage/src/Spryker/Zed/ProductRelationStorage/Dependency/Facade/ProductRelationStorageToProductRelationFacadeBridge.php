<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Dependency\Facade;

use Generated\Shared\Transfer\FilterTransfer;

class ProductRelationStorageToProductRelationFacadeBridge implements ProductRelationStorageToProductRelationFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface
     */
    protected $productRelationFacade;

    /**
     * @param \Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface $productRelationFacade
     */
    public function __construct($productRelationFacade)
    {
        $this->productRelationFacade = $productRelationFacade;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer[]
     */
    public function getProductRelationsByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->productRelationFacade->getProductRelationsByProductAbstractIds($productAbstractIds);
    }

    /**
     * @param int[] $productRelationIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductRelationIds(array $productRelationIds): array
    {
        return $this->productRelationFacade->getProductAbstractIdsByProductRelationIds($productRelationIds);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer[]
     */
    public function findProductRelationsForFilter(FilterTransfer $filterTransfer): array
    {
        return $this->productRelationFacade->findProductRelationsForFilter($filterTransfer);
    }
}
