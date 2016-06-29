<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;


class ProductManagementToPriceBridge implements ProductManagementToPriceInterface
{

    /**
     * @var \Spryker\Zed\Price\Business\PriceFacade
     */
    protected $priceFacade;

    /**
     * @param \Spryker\Zed\Price\Business\PriceFacade $priceFacade
     */
    public function __construct($priceFacade)
    {
        $this->priceFacade = $priceFacade;
    }

    /**
     * @api
     *
     * @param int $idAbstractProduct
     * @param null $priceType
     *
     * @return \Generated\Shared\Transfer\PriceProductAbstractTransfer|null
     */
    public function getProductAbstractPrice($idAbstractProduct, $priceType = null)
    {
        return $this->priceFacade->getProductAbstractPrice($idAbstractProduct, $priceType);
    }

    /**
     * @api
     *
     * @param int $idProduct
     * @param null $priceType
     *
     * @return \Generated\Shared\Transfer\PriceProductConcreteTransfer|null
     */
    public function getProductConcretePrice($idProduct, $priceType = null)
    {
        return $this->priceFacade->getProductConcretePrice($idProduct, $priceType);
    }
}
