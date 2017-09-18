<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyDiscountConnector\Communication;

use Spryker\Zed\CurrencyDiscountConnector\CurrencyDiscountConnectorDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CurrencyDiscountConnector\CurrencyDiscountConnectorConfig getConfig()
 * @method \Spryker\Zed\CurrencyDiscountConnector\Business\CurrencyDiscountConnectorFacade getFacade()
 */
class CurrencyDiscountConnectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\CurrencyDiscountConnector\Dependency\Facade\CurrencyDiscountConnectorToCurrencyInterface
     */
    public function getCurrencyFacade()
    {
        return $this->getProvidedDependency(CurrencyDiscountConnectorDependencyProvider::FACADE_CURRENCY);
    }

}
