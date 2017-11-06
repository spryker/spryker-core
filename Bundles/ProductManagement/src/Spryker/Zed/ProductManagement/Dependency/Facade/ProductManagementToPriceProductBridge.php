<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

class ProductManagementToPriceProductBridge implements ProductManagementToPriceProductInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface $priceProductFacade
     */
    public function __construct($priceProductFacade)
    {
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceTypeName = null)
    {
        return $this->priceProductFacade->getPriceBySku($sku, $priceTypeName);
    }

    /**
     * @return \Generated\Shared\Transfer\PriceTypeTransfer[]
     */
    public function getPriceTypeValues()
    {
        return $this->priceProductFacade->getPriceTypeValues();
    }

    /**
     * @return string
     */
    public function getDefaultPriceTypeName()
    {
        return $this->priceProductFacade->getDefaultPriceTypeName();
    }

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePrices($idProductConcrete, $idProductAbstract)
    {
        return $this->priceProductFacade->findProductConcretePrices($idProductConcrete, $idProductAbstract);
    }
}
