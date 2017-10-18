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
    protected $priceFacade;

    /**
     * @param \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface $priceFacade
     */
    public function __construct($priceFacade)
    {
        $this->priceFacade = $priceFacade;
    }

    /**
     * @param string $sku
     * @param string $priceTypeName
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceTypeName = null)
    {
        return $this->priceFacade->getPriceBySku($sku, $priceTypeName);
    }

    /**
     * @return \Generated\Shared\Transfer\PriceTypeTransfer[]
     */
    public function getPriceTypeValues()
    {
        return $this->priceFacade->getPriceTypeValues();
    }

    /**
     * @return string
     */
    public function getDefaultPriceTypeName()
    {
        return $this->priceFacade->getDefaultPriceTypeName();
    }

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePrices($idProductConcrete, $idProductAbstract)
    {
        return $this->priceFacade->findProductConcretePrices($idProductConcrete, $idProductAbstract);
    }

}
