<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade;

class ProductDiscontinuedProductLabelConnectorToProductBridge implements ProductDiscontinuedProductLabelConnectorToProductInterface
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
     * @return array
     */
    public function getProductAbstractIdByConcreteId(int $idProduct): int
    {
        return $this->productFacade->getProductAbstractIdByConcreteId($idProduct);
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getConcreteProductsByAbstractProductId(int $idProduct): array
    {
        return $this->productFacade->getConcreteProductsByAbstractProductId($idProduct);
    }
}
