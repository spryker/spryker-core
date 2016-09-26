<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

use Generated\Shared\Transfer\PriceProductTransfer;

interface ProductManagementToPriceInterface
{

    /**
     * @param int $idAbstractProduct
     * @param null $priceType
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function getProductAbstractPrice($idAbstractProduct, $priceType = null);

    /**
     * @param int $idProduct
     * @param null $priceType
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function getProductConcretePrice($idProduct, $priceType = null);

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceTransfer
     *
     * @return int
     */
    public function persistAbstractProductPrice(PriceProductTransfer $priceTransfer, $priceType = null);

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceTransfer
     * @param null $priceTypeName
     *
     * @throws \Exception
     *
     * @return int
     */
    public function persistConcreteProductPrice(PriceProductTransfer $priceTransfer, $priceTypeName = null);

}
