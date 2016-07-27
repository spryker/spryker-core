<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

use Generated\Shared\Transfer\ZedProductPriceTransfer;

interface ProductManagementToPriceInterface
{

    /**
     * @api
     *
     * @param int $idAbstractProduct
     * @param null $priceType
     *
     * @return \Generated\Shared\Transfer\ZedProductPriceTransfer|null
     */
    public function getProductAbstractPrice($idAbstractProduct, $priceType = null);

    /**
     * @api
     *
     * @param int $idProduct
     * @param null $priceType
     *
     * @return \Generated\Shared\Transfer\ZedProductPriceTransfer|null
     */
    public function getProductConcretePrice($idProduct, $priceType = null);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ZedProductPriceTransfer $priceTransfer
     *
     * @return int
     */
    public function persistAbstractProductPrice(ZedProductPriceTransfer $priceTransfer, $priceType = null);

}
