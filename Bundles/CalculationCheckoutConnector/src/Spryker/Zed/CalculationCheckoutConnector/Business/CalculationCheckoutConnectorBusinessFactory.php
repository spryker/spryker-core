<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CalculationCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\CalculationCheckoutConnector\CalculationCheckoutConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\CalculationCheckoutConnector\CalculationCheckoutConnectorConfig getConfig()
 */
class CalculationCheckoutConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\CalculationCheckoutConnector\Dependency\Facade\CalculationCheckoutConnectorToCalculationInterface
     */
    public function getCalculationFacade()
    {
        return $this->getProvidedDependency(CalculationCheckoutConnectorDependencyProvider::FACADE_CALCULATION);
    }

}
