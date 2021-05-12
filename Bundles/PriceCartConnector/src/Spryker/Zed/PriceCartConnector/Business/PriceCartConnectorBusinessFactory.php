<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceCartConnector\Business\Filter\Comparator\ItemComparator;
use Spryker\Zed\PriceCartConnector\Business\Filter\Comparator\ItemComparatorInterface;
use Spryker\Zed\PriceCartConnector\Business\Filter\ItemFilterInterface;
use Spryker\Zed\PriceCartConnector\Business\Filter\ItemsWithoutPriceFilter;
use Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilter;
use Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilterInterface;
use Spryker\Zed\PriceCartConnector\Business\Manager\PriceManager;
use Spryker\Zed\PriceCartConnector\Business\Sanitizer\SourcePriceSanitizer;
use Spryker\Zed\PriceCartConnector\Business\Sanitizer\SourcePriceSanitizerInterface;
use Spryker\Zed\PriceCartConnector\Business\Validator\PriceProductValidator;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToMessengerInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToPriceProductServiceInterface;
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
            $this->getPriceFacade(),
            $this->createPriceProductFilter(),
            $this->getPriceProductService(),
            $this->getPriceProductExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Business\Validator\PriceProductValidatorInterface
     */
    public function createPriceProductValidator()
    {
        return new PriceProductValidator(
            $this->getPriceProductFacade(),
            $this->createPriceProductFilter()
        );
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilterInterface
     */
    public function createPriceProductFilter(): PriceProductFilterInterface
    {
        return new PriceProductFilter(
            $this->getPriceProductFacade(),
            $this->getPriceFacade(),
            $this->getCurrencyFacade(),
            $this->getCartItemQuantityCounterStrategyPlugins(),
            $this->createItemComparator()
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
            $this->getMessengerFacade(),
            $this->getPriceProductService()
        );
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Business\Sanitizer\SourcePriceSanitizerInterface
     */
    public function createSourcePriceSanitizer(): SourcePriceSanitizerInterface
    {
        return new SourcePriceSanitizer();
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Business\Filter\Comparator\ItemComparatorInterface
     */
    public function createItemComparator(): ItemComparatorInterface
    {
        return new ItemComparator($this->getConfig());
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

    /**
     * @return \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): PriceCartConnectorToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(PriceCartConnectorDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToPriceProductServiceInterface
     */
    public function getPriceProductService(): PriceCartConnectorToPriceProductServiceInterface
    {
        return $this->getProvidedDependency(PriceCartConnectorDependencyProvider::SERVICE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\PriceCartConnectorExtension\Dependency\Plugin\PriceProductExpanderPluginInterface[]
     */
    public function getPriceProductExpanderPlugins(): array
    {
        return $this->getProvidedDependency(PriceCartConnectorDependencyProvider::PLUGINS_PRICE_PRODUCT_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\PriceCartConnectorExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface[]
     */
    public function getCartItemQuantityCounterStrategyPlugins(): array
    {
        return $this->getProvidedDependency(PriceCartConnectorDependencyProvider::PLUGINS_CART_ITEM_QUANTITY_COUNTER_STRATEGY);
    }
}
