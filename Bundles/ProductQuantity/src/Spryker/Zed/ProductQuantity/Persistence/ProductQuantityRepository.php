<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductQuantityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductQuantity\Persistence\ProductQuantityPersistenceFactory getFactory()
 */
class ProductQuantityRepository extends AbstractRepository implements ProductQuantityRepositoryInterface
{
    /**
     * @uses SpyProductQuery
     *
     * @param string[] $productSkus
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer[]
     */
    public function findProductQuantityTransfersByProductSku(array $productSkus): array
    {
        if (!$productSkus) {
            return [];
        }

        $query = $this->getFactory()
            ->createProductQuantityQuery()
            ->joinWithProduct()
            ->useProductQuery()
                ->filterBySku_In($productSkus)
            ->endUse();

        $productQuantityEntityTransfers = $this->buildQueryFromCriteria($query)->find();

        return $this->getMappedProductQuantityTransfers($productQuantityEntityTransfers);
    }

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer[]
     */
    public function findProductQuantityTransfersByProductIds(array $productIds): array
    {
        if (!$productIds) {
            return [];
        }

        $query = $this->getFactory()
            ->createProductQuantityQuery()
            ->filterByFkProduct_In($productIds);

        $productQuantityEntityTransfers = $this->buildQueryFromCriteria($query)->find();

        return $this->getMappedProductQuantityTransfers($productQuantityEntityTransfers);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer[]
     */
    public function findProductQuantityTransfers(): array
    {
        $query = $this->getFactory()->createProductQuantityQuery();
        $productQuantityEntityTransfers = $this->buildQueryFromCriteria($query)->find();

        return $this->getMappedProductQuantityTransfers($productQuantityEntityTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer[]
     */
    public function findFilteredProductQuantityTransfers(FilterTransfer $filterTransfer): array
    {
        $productQuantityEntityTransfers = $this->buildQueryFromCriteria(
            $this->getFactory()->createProductQuantityQuery(),
            $filterTransfer
        )->find();

        return $this->getMappedProductQuantityTransfers($productQuantityEntityTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer[] $productQuantityEntityTransfers
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer[]
     */
    protected function getMappedProductQuantityTransfers(array $productQuantityEntityTransfers): array
    {
        $productQuantityTransfers = [];
        $mapper = $this->getFactory()->createProductQuantityMapper();

        foreach ($productQuantityEntityTransfers as $productQuantityEntityTransfer) {
            $productQuantityTransfers[] = $mapper->mapProductQuantityTransfer(
                $productQuantityEntityTransfer,
                new ProductQuantityTransfer()
            );
        }

        return $productQuantityTransfers;
    }
}
