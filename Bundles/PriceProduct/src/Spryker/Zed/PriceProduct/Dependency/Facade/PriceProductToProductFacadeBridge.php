<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Dependency\Facade;

class PriceProductToProductFacadeBridge implements PriceProductToProductFacadeInterface
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
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductAbstractIdBySku($sku)
    {
        return $this->productFacade->findProductAbstractIdBySku($sku);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductFacadeBridge::findProductConcreteIdBySku()} instead.
     *
     * @param string $sku
     *
     * @return int|null
     */
    public function getProductConcreteIdBySku($sku)
    {
        return $this->productFacade->findProductConcreteIdBySku($sku);
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductAbstract($sku)
    {
        return $this->productFacade->hasProductAbstract($sku);
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductConcrete($sku)
    {
        return $this->productFacade->hasProductConcrete($sku);
    }

    /**
     * @param string $sku
     *
     * @return string
     */
    public function getAbstractSkuFromProductConcrete($sku)
    {
        return $this->productFacade->getAbstractSkuFromProductConcrete($sku);
    }

    /**
     * @param string $concreteSku
     *
     * @return int
     */
    public function getProductAbstractIdByConcreteSku($concreteSku)
    {
        return $this->productFacade->getProductAbstractIdByConcreteSku($concreteSku);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return int
     */
    public function getProductAbstractIdByConcreteId(int $idProductConcrete): int
    {
        return $this->productFacade->getProductAbstractIdByConcreteId($idProductConcrete);
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
