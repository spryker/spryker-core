<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Status;

use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;

class ProductAbstractStatusChecker implements ProductAbstractStatusCheckerInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\Product\Persistence\ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer, ProductRepositoryInterface $productRepository)
    {
        $this->productQueryContainer = $productQueryContainer;
        $this->productRepository = $productRepository;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isActive($idProductAbstract)
    {
        $productConcreteCollection = $this->productQueryContainer
            ->queryProduct()
            ->findByFkProductAbstract($idProductAbstract);

        foreach ($productConcreteCollection as $productConcreteEntity) {
            if ($productConcreteEntity->getIsActive()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    public function filterActiveIds(array $productAbstractIds): array
    {
        $activeProductAbstractIds = [];
        $activeProductAbstractTransfers = $this->productRepository->getActiveProductAbstractsByProductAbstractIds($productAbstractIds);
        foreach ($activeProductAbstractTransfers as $productAbstractTransfer) {
            $activeProductAbstractIds[] = $productAbstractTransfer->getIdProductAbstract();
        }

        return array_diff($productAbstractIds, $activeProductAbstractIds);
    }
}
