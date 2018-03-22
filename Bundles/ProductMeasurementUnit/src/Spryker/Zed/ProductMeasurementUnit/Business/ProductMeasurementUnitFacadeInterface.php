<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitExchangeDetailTransfer;

interface ProductMeasurementUnitFacadeInterface
{
    /**
     * Specification:
     * -
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitExchangeDetailTransfer $exchangeDetailTransfer
     *
     * @throws \Braintree\Exception
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitExchangeDetailTransfer
     */
    public function getExchangeDetail(ProductMeasurementUnitExchangeDetailTransfer $exchangeDetailTransfer);

    /**
     * Specification:
     * -
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateProductMeasurementSalesUnit(CartChangeTransfer $cartChangeTransfer);
}
