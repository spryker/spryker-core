<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartProductListConnector\Business;

use Spryker\Zed\CartProductListConnector\Business\ProductListRestrictionValidator\ProductListRestrictionValidator;
use Spryker\Zed\CartProductListConnector\Business\ProductListRestrictionValidator\ProductListRestrictionValidatorInterface;
use Spryker\Zed\CartProductListConnector\Business\RestrictedItemsFilter\RestrictedItemsFilter;
use Spryker\Zed\CartProductListConnector\Business\RestrictedItemsFilter\RestrictedItemsFilterInterface;
use Spryker\Zed\CartProductListConnector\CartProductListConnectorDependencyProvider;
use Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToMessengerFacadeInterface;
use Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToProductFacadeInterface;
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
            $this->createProductListRestrictionValidator()
        );
    }

    /**
     * @return \Spryker\Zed\CartProductListConnector\Business\ProductListRestrictionValidator\ProductListRestrictionValidatorInterface
     */
    public function createProductListRestrictionValidator(): ProductListRestrictionValidatorInterface
    {
        return new ProductListRestrictionValidator(
            $this->getProductFacade(),
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
     * @return \Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToProductFacadeInterface
     */
    public function getProductFacade(): CartProductListConnectorToProductFacadeInterface
    {
        return $this->getProvidedDependency(CartProductListConnectorDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\CartProductListConnector\Dependency\Facade\CartProductListConnectorToProductListFacadeInterface
     */
    public function getProductListFacade(): CartProductListConnectorToProductListFacadeInterface
    {
        return $this->getProvidedDependency(CartProductListConnectorDependencyProvider::FACADE_PRODUCT_LIST);
    }
}
