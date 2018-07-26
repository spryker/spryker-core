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
     * @return null|int
     */
    public function findProductAbstractIdByConcreteId(int $idProduct): ?int
    {
        return $this->productFacade->findProductAbstractIdByConcreteId($idProduct);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function findProductConcreteIdsByAbstractProductId(int $idProductAbstract): array
    {
        return $this->productFacade->findProductConcreteIdsByAbstractProductId($idProductAbstract);
    }
}
