<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceCartConnector\Business\Filter\ItemFilterInterface;
use Spryker\Zed\PriceCartConnector\Business\Filter\ItemsWithoutPriceFilter;
use Spryker\Zed\PriceCartConnector\Business\Manager\PriceManager;
use Spryker\Zed\PriceCartConnector\Business\Validator\PriceProductValidator;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToMessengerInterface;
use Spryker\Zed\PriceCartConnector\PriceCartConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorBusinessFactory getFactory()
 * @method \Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig getConfig()
 */
class PriceCartConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PriceCartConnector\Business\Manager\PriceManagerInterface
     */
    public function createPriceManager()
    {
        return new PriceManager(
            $this->getPriceProductFacade(),
            $this->getPriceFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Business\Validator\PriceProductValidatorInterface
     */
    public function createPriceProductValidator()
    {
        return new PriceProductValidator(
            $this->getPriceProductFacade(),
            $this->getPriceFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Business\Filter\ItemFilterInterface
     */
    public function createItemsWithoutPriceFilter(): ItemFilterInterface
    {
        return new ItemsWithoutPriceFilter(
            $this->getPriceFacade(),
            $this->getPriceProductFacade(),
            $this->getMessengerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface
     */
    protected function getPriceProductFacade()
    {
        return $this->getProvidedDependency(PriceCartConnectorDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface
     */
    protected function getPriceFacade()
    {
        return $this->getProvidedDependency(PriceCartConnectorDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToMessengerInterface
     */
    public function getMessengerFacade(): PriceCartToMessengerInterface
    {
        return $this->getProvidedDependency(PriceCartConnectorDependencyProvider::FACADE_MESSENGER);
    }
}
