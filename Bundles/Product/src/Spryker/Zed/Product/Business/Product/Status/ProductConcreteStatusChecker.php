<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Status;

use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductConcreteStatusChecker implements ProductConcreteStatusCheckerInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer)
    {
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function isActive(string $sku): bool
    {
        return $this->productQueryContainer
            ->queryProduct()
            ->findOneBySku($sku)
            ->getIsActive();
    }
}
