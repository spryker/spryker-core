<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\ProductConcreteReader;

use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;

class ProductConcreteReader implements ProductConcreteReaderInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function findProductConcreteByIds(array $ids): array
    {
        return $this->productRepository->findProductConcreteByIds($ids);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function findAllProductConcrete(): array
    {
        return $this->productRepository->findAllProductConcrete();
    }
}
