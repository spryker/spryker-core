<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnitValidator;
use Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementUnitExchanger;

/**
 * @method \Spryker\Zed\ProductMeasurementUnit\ProductMeasurementUnitConfig getConfig()
 */
class ProductMeasurementUnitBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementUnitExchangerInterface
     */
    public function createProductMeasurementUnitExchanger()
    {
        return new ProductMeasurementUnitExchanger($this->getConfig()::MEASUREMENT_UNIT_EXCHANGE_COLLECTION);
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementSalesUnitValidatorInterface
     */
    public function createProductMeasurementSalesUnitValidator()
    {
        return new ProductMeasurementSalesUnitValidator();
    }
}
