<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartProductListConnector\Business;

use Spryker\Zed\CartProductListConnector\Business\RestrictedItemsFilter\RestrictedItemsFilter;
use Spryker\Zed\CartProductListConnector\Business\RestrictedItemsFilter\RestrictedItemsFilterInterface;
use Spryker\Zed\CartProductListConnector\CartProductListConnectorDependencyProvider;
use Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToMessengerFacadeInterface;
use Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToProductListFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class CartProductListConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CartProductListConnector\Business\RestrictedItemsFilter\RestrictedItemsFilterInterface
     */
    public function createRestrictedItemsFilter(): RestrictedItemsFilterInterface
    {
        return new RestrictedItemsFilter(
            $this->getMessengerFacade(),
            $this->getProductListFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToMessengerFacadeInterface
     */
    public function getMessengerFacade(): CartProductListConnectorToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(CartProductListConnectorDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToProductListFacadeInterface
     */
    public function getProductListFacade(): CartProductListConnectorToProductListFacadeInterface
    {
        return $this->getProvidedDependency(CartProductListConnectorDependencyProvider::FACADE_PRODUCT_LIST);
    }
}
