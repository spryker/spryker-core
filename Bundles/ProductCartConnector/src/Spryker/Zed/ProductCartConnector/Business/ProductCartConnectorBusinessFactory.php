<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductCartConnector\Business\Expander\ProductExpander;
use Spryker\Zed\ProductCartConnector\Business\InactiveItemsFilter\InactiveItemsFilter;
use Spryker\Zed\ProductCartConnector\Business\InactiveItemsFilter\InactiveItemsFilterInterface;
use Spryker\Zed\ProductCartConnector\Business\Validator\ProductValidator;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToMessengerFacadeInterface;
use Spryker\Zed\ProductCartConnector\ProductCartConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductCartConnector\ProductCartConnectorConfig getConfig()
 */
class ProductCartConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductCartConnector\Business\Expander\ProductExpanderInterface
     */
    public function createProductExpander()
    {
        return new ProductExpander(
            $this->getLocaleFacade(),
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductCartConnector\Business\Validator\ProductValidatorInterface
     */
    public function createProductValidator()
    {
        return new ProductValidator(
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductCartConnector\Business\InactiveItemsFilter\InactiveItemsFilterInterface
     */
    public function createInactiveItemsFilter(): InactiveItemsFilterInterface
    {
        return new InactiveItemsFilter(
            $this->getProductFacade(),
            $this->getMessengerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductCartConnectorDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(ProductCartConnectorDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToMessengerFacadeInterface
     */
    public function getMessengerFacade(): ProductCartConnectorToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(ProductCartConnectorDependencyProvider::FACADE_MESSENGER);
    }
}
