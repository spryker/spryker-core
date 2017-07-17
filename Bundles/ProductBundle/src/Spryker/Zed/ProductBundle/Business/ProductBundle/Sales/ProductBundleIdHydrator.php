<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Sales;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToProductQueryContainerInterface;

class ProductBundleIdHydrator implements ProductBundleIdHydratorInterface
{

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(
        ProductBundleToProductQueryContainerInterface $productQueryContainer
    ) {
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrate(OrderTransfer $orderTransfer)
    {
        foreach ($orderTransfer->getBundleItems() as $item) {
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
        return $this->productQueryContainer->queryProductConcreteBySku($sku)->findOne();
    }

}
