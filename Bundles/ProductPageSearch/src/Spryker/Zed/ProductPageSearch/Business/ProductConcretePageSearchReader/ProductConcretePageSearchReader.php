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
    public function findAllProductConcretePageSearchTransfers(): array
    {
        return $this->repository->findProductConcretePageSearchTransfers();
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return array
     */
    public function findProductConcretePageSearchTransfersByProductConcreteIds(array $productConcreteIds): array
    {
        return $this->repository->findProductConcretePageSearchTransfers($productConcreteIds);
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return array
     */
    public function findProductConcretePageSearchTransfersByProductConcreteIdsGrouppedByStoreAndLocale(array $productConcreteIds): array
    {
        $productConcreteSearchPageTransfers = $this->repository->findProductConcretePageSearchTransfers($productConcreteIds);

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
            $idProduct = $productConcretePageSearchTransfer->getFkProduct();
            $store = $productConcretePageSearchTransfer->getStore();
            $locale = $productConcretePageSearchTransfer->getLocale();

            $grouppedProductConcretePageSearchTransfers[$idProduct][$store][$locale] = $productConcretePageSearchTransfer;
        }

        return $grouppedProductConcretePageSearchTransfers;
    }
}
