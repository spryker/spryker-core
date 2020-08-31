<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCalculationConnector\Business;

use Spryker\Zed\DiscountCalculationConnector\Business\Calculator\Discount;
use Spryker\Zed\DiscountCalculationConnector\Business\Calculator\DiscountInterface;
use Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToDiscountInterface;
use Spryker\Zed\DiscountCalculationConnector\DiscountCalculationConnectorDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\DiscountCalculationConnector\DiscountCalculationConnectorConfig getConfig()
 */
class DiscountCalculationConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\DiscountCalculationConnector\Business\Calculator\DiscountInterface
     */
    public function createDiscount(): DiscountInterface
    {
        return new Discount(
            $this->getDiscountFacade()
        );
    }

    /**
     * @return \Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToDiscountInterface
     */
    public function getDiscountFacade(): DiscountCalculationToDiscountInterface
    {
        return $this->getProvidedDependency(DiscountCalculationConnectorDependencyProvider::FACADE_DISCOUNT);
    }
}
