<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader;

use Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface;

class ProductConcretePageSearchReader implements ProductConcretePageSearchReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface $repository
     */
    public function __construct(ProductPageSearchRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    public function findAllProductConcretePageSearchEntities(): array
    {
        return $this->repository->findProductConcretePageSearchEntities();
    }

    /**
     * @param int[] $ids
     *
     * @return array
     */
    public function findProductConcretePageSearchEntitiesByProductConcreteIds(array $ids): array
    {
        $productConcreteSearchPageTransfers = $this->repository->findProductConcretePageSearchEntities($ids);

        return $this->getTransfersGrouppedByStoreAndLocale($productConcreteSearchPageTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[] $productConcretePageSearchTransfers
     *
     * @return array
     */
    protected function getTransfersGrouppedByStoreAndLocale(array $productConcretePageSearchTransfers): array
    {
        $grouppedProductConcretePageSearchTransfers = [];

        foreach ($productConcretePageSearchTransfers as $productConcretePageSearchTransfer) {
            $grouppedProductConcretePageSearchTransfers[$productConcretePageSearchTransfer->getFkProduct()][$productConcretePageSearchTransfer->getStore()][$productConcretePageSearchTransfer->getLocale()] = $productConcretePageSearchTransfer;
        }

        return $grouppedProductConcretePageSearchTransfers;
    }
}
