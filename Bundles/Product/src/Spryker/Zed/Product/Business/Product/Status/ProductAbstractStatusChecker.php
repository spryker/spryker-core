<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Status;

use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductAbstractStatusChecker implements ProductAbstractStatusCheckerInterface
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
}
