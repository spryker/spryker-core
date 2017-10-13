<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

interface ProductManagementToPriceInterface
{
    /**
     * @param int $idAbstractProduct
     * @param string|null $priceType
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findProductAbstractPrice($idAbstractProduct, $priceType = null);

    /**
     * @param int $idProduct
     * @param string|null $priceType
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findProductConcretePrice($idProduct, $priceType = null);

    /**
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceTypeName = null);

    /**
     * @return array
     */
    public function getPriceTypeValues();

    /**
     * @return string
     */
    public function getDefaultPriceTypeName();
}
