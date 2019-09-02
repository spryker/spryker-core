<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency\Facade;

class ProductPackagingUnitToProductFacadeBridge implements ProductPackagingUnitToProductFacadeInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\Product\Business\ProductFacadeInterface $productFacade
     */
    public function __construct($productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcreteById($idProduct)
    {
        return $this->productFacade->findProductConcreteById($idProduct);
    }

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductConcreteIdBySku($sku)
    {
        return $this->productFacade->findProductConcreteIdBySku($sku);
    }
}
