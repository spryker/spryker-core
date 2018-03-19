<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model;

use Generated\Shared\Transfer\ProductMeasurementUnitExchangeDetailTransfer;

interface ProductMeasurementUnitExchangerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitExchangeDetailTransfer $exchangeDetailTransfer
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitExchangeDetailTransfer
     */
    public function getExchangeDetail(ProductMeasurementUnitExchangeDetailTransfer $exchangeDetailTransfer);
}
