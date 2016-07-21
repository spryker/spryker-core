<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Ratepay\RatepayDependencyProvider;

/**
 * @method \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Ratepay\RatepayConfig getConfig()
 */
class RatepayCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToSalesAggregatorInterface
     */
    public function getSalesAggregator()
    {
        return $this->getProvidedDependency(RatepayDependencyProvider::FACADE_SALES_AGGREGATOR);
    }

}
