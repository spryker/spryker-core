<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Dependency\QueryContainer;

class AvailabilityGuiToProductBundleQueryContainerBridge implements AvailabilityGuiToProductBundleQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected $productBundleQueryContainter;

    /**
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface $productBundleQueryContainter
     */
    public function __construct($productBundleQueryContainter)
    {
        $this->productBundleQueryContainter = $productBundleQueryContainter;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundleQuery
     */
    public function queryBundleProduct($idProductConcrete)
    {
        return $this->productBundleQueryContainter->queryBundleProduct($idProductConcrete);
    }
}
