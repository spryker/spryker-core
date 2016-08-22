<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

use Generated\Shared\Transfer\ZedProductPriceTransfer;

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
     * @param null $priceType
     *
     * @return \Generated\Shared\Transfer\ZedProductPriceTransfer|null
     */
    public function getProductAbstractPrice($idAbstractProduct, $priceType = null)
    {
        return $this->priceFacade->getProductAbstractPrice($idAbstractProduct, $priceType);
    }

    /**
     * @param int $idProduct
     * @param null $priceType
     *
     * @return \Generated\Shared\Transfer\ZedProductPriceTransfer|null
     */
    public function getProductConcretePrice($idProduct, $priceType = null)
    {
        return $this->priceFacade->getProductConcretePrice($idProduct, $priceType);
    }

    /**
     * @param \Generated\Shared\Transfer\ZedProductPriceTransfer $priceTransfer
     *
     * @return int
     */
    public function persistAbstractProductPrice(ZedProductPriceTransfer $priceTransfer, $priceType = null)
    {
        return $this->priceFacade->persistAbstractProductPrice($priceTransfer, $priceType);
    }

    /**
     * @param \Generated\Shared\Transfer\ZedProductPriceTransfer $priceTransfer
     *
     * @return int
     */
    public function persistConcreteProductPrice(ZedProductPriceTransfer $priceTransfer, $priceType = null)
    {
        return $this->priceFacade->persistConcreteProductPrice($priceTransfer, $priceType);
    }

}
