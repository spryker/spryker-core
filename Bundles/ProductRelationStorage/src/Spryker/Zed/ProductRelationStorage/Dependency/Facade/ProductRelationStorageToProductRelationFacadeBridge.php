<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Dependency\Facade;

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
    public function getProductRelationsByIdProductAbstracts(array $productAbstractIds): array
    {
        return $this->productRelationFacade->getProductRelationsByIdProductAbstracts($productAbstractIds);
    }
}
