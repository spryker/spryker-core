<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCalculationConnector\Communication;

use Spryker\Zed\DiscountCalculationConnector\DiscountCalculationConnectorDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\DiscountCalculationConnector\DiscountCalculationConnectorConfig getConfig()
 * @method \Spryker\Zed\DiscountCalculationConnector\Business\DiscountCalculationConnectorFacadeInterface getFacade()
 */
class DiscountCalculationConnectorCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountCalculationToDiscountInterface
     */
    public function getDiscountFacade()
    {
        return $this->getProvidedDependency(DiscountCalculationConnectorDependencyProvider::FACADE_DISCOUNT);
    }
}
