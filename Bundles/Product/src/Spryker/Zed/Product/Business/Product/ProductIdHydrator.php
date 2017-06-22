<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductIdHydrator
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateProductIds(OrderTransfer $orderTransfer)
    {
        foreach ($orderTransfer->getItems() as $item) {
            $concreteProduct = $this->findConcreteProduct($item->getSku());

            if (!$concreteProduct) {
                continue;
            }

            $item->setIdProductAbstract($concreteProduct->getFkProductAbstract());
            $item->setId($concreteProduct->getIdProduct());
        }

        return $orderTransfer;
    }

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct|null
     */
    protected function findConcreteProduct($sku)
    {
        return $this->productQueryContainer
            ->queryProductConcreteBySku($sku)
            ->findOne();
    }

}
