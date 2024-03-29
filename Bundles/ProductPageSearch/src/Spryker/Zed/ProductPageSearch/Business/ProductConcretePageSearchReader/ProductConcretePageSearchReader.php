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
     * @param array<int> $productIds
     *
     * @return array
     */
    public function getProductConcretePageSearchTransfersByProductIds(array $productIds): array
    {
        return $this->repository->getProductConcretePageSearchTransfers($productIds);
    }

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array
     */
    public function getProductConcretePageSearchTransfersByProductIdsGrouppedByStoreAndLocale(array $productConcreteIds): array
    {
        $productConcreteSearchPageTransfers = $this->repository->getProductConcretePageSearchTransfers($productConcreteIds);

        return $this->getTransfersGroupedByStoreAndLocale($productConcreteSearchPageTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcretePageSearchTransfer> $productConcretePageSearchTransfers
     *
     * @return array
     */
    protected function getTransfersGroupedByStoreAndLocale(array $productConcretePageSearchTransfers): array
    {
        $groupedProductConcretePageSearchTransfers = [];

        foreach ($productConcretePageSearchTransfers as $productConcretePageSearchTransfer) {
            $idProduct = $productConcretePageSearchTransfer->getFkProduct();
            $storeName = $productConcretePageSearchTransfer->getStore();
            $localeName = $productConcretePageSearchTransfer->getLocale();

            $groupedProductConcretePageSearchTransfers[$idProduct][$storeName][$localeName] = $productConcretePageSearchTransfer;
        }

        return $groupedProductConcretePageSearchTransfers;
    }

    /**
     * @param array $productAbstractStoreMap Keys are product abstract IDs, values are store IDs.
     *
     * @return array<\Generated\Shared\Transfer\ProductConcretePageSearchTransfer>
     */
    public function getProductConcretePageSearchTransfersByProductAbstractStoreMap(array $productAbstractStoreMap): array
    {
        return $this->repository->getProductConcretePageSearchTransfersByProductAbstractStoreMap($productAbstractStoreMap);
    }
}
