<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCartConnector\Business;

use Spryker\Zed\DiscountCartConnector\Business\QuoteChangeObserver\QuoteChangeObserver;
use Spryker\Zed\DiscountCartConnector\Business\QuoteChangeObserver\QuoteChangeObserverInterface;
use Spryker\Zed\DiscountCartConnector\DiscountCartConnectorDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\DiscountCartConnector\DiscountCartConnectorConfig getConfig()
 */
class DiscountCartConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\DiscountCartConnector\Business\QuoteChangeObserver\QuoteChangeObserverInterface
     */
    public function createQuoteChangeObserver(): QuoteChangeObserverInterface
    {
        return new QuoteChangeObserver($this->getMessengerFacade());
    }

    /**
     * @return \Spryker\Zed\DiscountCartConnector\Dependency\Facade\DiscountCartConnectorToMessengerFacadeInterface
     */
    public function getMessengerFacade()
    {
        return $this->getProvidedDependency(DiscountCartConnectorDependencyProvider::FACADE_MESSENGER);
    }
}
