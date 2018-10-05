<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Status;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;

class ProductConcreteStatusChecker implements ProductConcreteStatusCheckerInterface
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
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return bool
     */
    public function isActive(ProductConcreteTransfer $productConcreteTransfer): bool
    {
        return $this->productRepository->isProductConcreteActive($productConcreteTransfer);
    }
}
