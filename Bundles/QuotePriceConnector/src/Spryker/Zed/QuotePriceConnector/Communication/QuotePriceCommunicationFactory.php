<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuotePriceConnector\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\QuotePriceConnector\Dependency\Facade\QuotePriceConnectorToPriceFacadeInterface;
use Spryker\Zed\QuotePriceConnector\QuotePriceConnectorDependencyProvider;

class QuotePriceCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\QuotePriceConnector\Dependency\Facade\QuotePriceConnectorToPriceFacadeInterface
     */
    public function getPriceFacade(): QuotePriceConnectorToPriceFacadeInterface
    {
        return $this->getProvidedDependency(QuotePriceConnectorDependencyProvider::FACADE_PRICE);
    }
}
