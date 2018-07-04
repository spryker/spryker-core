<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Business\ProductListStorage;

use Spryker\Zed\ProductListStorage\Business\ProductListProductAbstractStorage\ProductListProductAbstractStorageWriterInterface;
use Spryker\Zed\ProductListStorage\Business\ProductListProductConcreteStorage\ProductListProductConcreteStorageWriterInterface;
use Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductListFacadeInterface;
use Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface;

class ProductListStorageWriter implements ProductListStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductListStorage\Business\ProductListProductAbstractStorage\ProductListProductAbstractStorageWriterInterface
     */
    protected $productListProductAbstractStorageWriter;

    /**
     * @var \Spryker\Zed\ProductListStorage\Business\ProductListProductConcreteStorage\ProductListProductConcreteStorageWriterInterface
     */
    protected $productListProductConcreteStorageWriter;

    /**
     * @var \Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductListFacadeInterface
     */
    protected $productListFacade;

    /**
     * @var \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface
     */
    protected $productListStorageRepository;

    /**
     * @param \Spryker\Zed\ProductListStorage\Business\ProductListProductAbstractStorage\ProductListProductAbstractStorageWriterInterface $productListProductAbstractStorageWriter
     * @param \Spryker\Zed\ProductListStorage\Business\ProductListProductConcreteStorage\ProductListProductConcreteStorageWriterInterface $productListProductConcreteStorageWriter
     * @param \Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductListFacadeInterface $productListFacade
     * @param \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface $productListStorageRepository
     */
    public function __construct(
        ProductListProductAbstractStorageWriterInterface $productListProductAbstractStorageWriter,
        ProductListProductConcreteStorageWriterInterface $productListProductConcreteStorageWriter,
        ProductListStorageToProductListFacadeInterface $productListFacade,
        ProductListStorageRepositoryInterface $productListStorageRepository
    ) {
        $this->productListProductAbstractStorageWriter = $productListProductAbstractStorageWriter;
        $this->productListProductConcreteStorageWriter = $productListProductConcreteStorageWriter;
        $this->productListFacade = $productListFacade;
        $this->productListStorageRepository = $productListStorageRepository;
    }

    /**
     * @param int[] $productListIds
     *
     * @return void
     */
    public function publish(array $productListIds): void
    {
        $productAbstractIds = $this->productListFacade->getProductAbstractIdsByProductListIds($productListIds);
        $this->publishAbstractProducts($productAbstractIds);
        $productConcreteIds = $this->findProductConcreteIds($productListIds, $productAbstractIds);
        $this->publishConcreteProducts($productConcreteIds);
    }

    /**
     * @param int[] $productListIds
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    protected function findProductConcreteIds(array $productListIds, array $productAbstractIds): array
    {
        return array_unique(
            array_merge(
                $this->productListStorageRepository->findProductConcreteIdsByProductAbstractIds($productAbstractIds),
                $this->productListStorageRepository->findProductConcreteIdsByProductListIds($productListIds)
            )
        );
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    protected function publishAbstractProducts(array $productAbstractIds): void
    {
        if (!count($productAbstractIds)) {
            return;
        }
        $this->productListProductAbstractStorageWriter->publish($productAbstractIds);
    }

    /**
     * @param array $productConcreteIds
     *
     * @return void
     */
    protected function publishConcreteProducts(array $productConcreteIds): void
    {
        if (!count($productConcreteIds)) {
            return;
        }
        $this->productListProductConcreteStorageWriter->publish($productConcreteIds);
    }
}
