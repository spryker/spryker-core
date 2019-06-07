<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Business\Storage;

use Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToProductOptionFacadeInterface;

class ProductOptionStorageReader implements ProductOptionStorageReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToProductOptionFacadeInterface
     */
    protected $productOptionFacade;

    /**
     * @param \Spryker\Zed\ProductOptionStorage\Dependency\Facade\ProductOptionStorageToProductOptionFacadeInterface $productOptionFacade
     */
    public function __construct(ProductOptionStorageToProductOptionFacadeInterface $productOptionFacade)
    {
        $this->productOptionFacade = $productOptionFacade;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getProductOptionGroupStatusesByProductAbstractIds(array $productAbstractIds): array
    {
        $productAbstractOptionGroupStatusTransfers = $this->productOptionFacade->getProductAbstractOptionGroupStatusesByProductAbstractIds(
            $productAbstractIds
        );

        return $this->getIndexedProductAbstractOptionGroupStatuses($productAbstractOptionGroupStatusTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionGroupStatusTransfer[] $productAbstractOptionGroupStatusTransfers
     *
     * @return array [[fkProductAbstract => [productOptionGroupName => productOptionGroupStatus]]]
     */
    protected function getIndexedProductAbstractOptionGroupStatuses(array $productAbstractOptionGroupStatusTransfers): array
    {
        $indexedProductAbstractOptionGroupStatuses = [];
        foreach ($productAbstractOptionGroupStatusTransfers as $productAbstractOptionGroupStatusTransfer) {
            $idProductAbstract = $productAbstractOptionGroupStatusTransfer->getIdProductAbstract();
            $productOptionGroupName = $productAbstractOptionGroupStatusTransfer->getProductOptionGroupName();

            $indexedProductAbstractOptionGroupStatuses[$idProductAbstract][$productOptionGroupName] = $productAbstractOptionGroupStatusTransfer->getIsActive();
        }

        return $indexedProductAbstractOptionGroupStatuses;
    }
}
