<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

class ProductManagementToPriceBridge implements ProductManagementToPriceInterface
{

    /**
     * @var \Spryker\Zed\Price\Business\PriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @param \Spryker\Zed\Price\Business\PriceFacadeInterface $priceFacade
     */
    public function __construct($priceFacade)
    {
        $this->priceFacade = $priceFacade;
    }

    /**
     * @param int $idAbstractProduct
     * @param string|null $priceType
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findProductAbstractPrice($idAbstractProduct, $priceType = null)
    {
        return $this->priceFacade->findProductAbstractPrice($idAbstractProduct, $priceType);
    }

    /**
     * @param int $idProduct
     * @param string|null $priceType
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findProductConcretePrice($idProduct, $priceType = null)
    {
        return $this->priceFacade->findProductConcretePrice($idProduct, $priceType);
    }

    /**
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceTypeName = null)
    {
        return $this->priceFacade->getPriceBySku($sku, $priceTypeName);
    }

    /**
     * @return array
     */
    public function getPriceTypeValues()
    {
        return $this->priceFacade->getPriceTypeValues();
    }

}
