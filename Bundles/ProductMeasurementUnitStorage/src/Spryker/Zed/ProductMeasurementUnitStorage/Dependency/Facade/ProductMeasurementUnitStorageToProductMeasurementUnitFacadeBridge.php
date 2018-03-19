<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade;

use Generated\Shared\Transfer\ProductMeasurementUnitExchangeDetailTransfer;

class ProductMeasurementUnitStorageToProductMeasurementUnitFacadeBridge implements ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Business\ProductMeasurementUnitFacadeInterface
     */
    protected $productMeasurementUnitFacade;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Business\ProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade
     */
    public function __construct($productMeasurementUnitFacade)
    {
        $this->productMeasurementUnitFacade = $productMeasurementUnitFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitExchangeDetailTransfer $exchangeDetailTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitExchangeDetailTransfer
     */
    public function getExchangeDetail(ProductMeasurementUnitExchangeDetailTransfer $exchangeDetailTransfer)
    {
        return $this->productMeasurementUnitFacade->getExchangeDetail($exchangeDetailTransfer);
    }
}
