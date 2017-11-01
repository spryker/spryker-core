<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceCartConnector\Business\Fixture;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Zed\PriceProduct\Business\PriceProductFacade;

class PriceProductFacadeStub extends PriceProductFacade
{
    /**
     * @var array
     */
    private $prices = [];

    /**
     * @var array
     */
    private $validities = [];

    /**
     * @param string $sku
     * @param string|null $priceType
     *
     * @return mixed
     */
    public function getPriceBySku($sku, $priceType = null)
    {
        return $this->prices[$sku];
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return mixed
     */
    public function getPriceFor(PriceProductFilterTransfer $priceFilterTransfer)
    {
        if (!isset($this->prices[$priceFilterTransfer->getSku()])) {
            return null;
        }
        return $this->prices[$priceFilterTransfer->getSku()];
    }

    /**
     * @param string $sku
     * @param string|null $priceType
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceType = null)
    {
        if (!isset($this->validities[$sku])) {
            return false;
        }

        return $this->validities[$sku];
    }

    /**
     * @param string $sku
     * @param int $price
     *
     * @return void
     */
    public function addPriceStub($sku, $price)
    {
        $this->prices[$sku] = $price;
    }

    /**
     * @param string $sku
     * @param bool $validity
     *
     * @return void
     */
    public function addValidityStub($sku, $validity = true)
    {
        $this->validities[$sku] = $validity;
    }

    /**
     * @return string
     */
    public function getDefaultPriceTypeName()
    {
        return 'DEFAULT';
    }

}
