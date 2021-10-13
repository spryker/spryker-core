<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationCart\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductConfigurationCart\Business\Checker\ProductConfigurationChecker;
use Spryker\Zed\ProductConfigurationCart\Business\Checker\ProductConfigurationCheckerInterface;
use Spryker\Zed\ProductConfigurationCart\Business\Comparator\ItemComparator;
use Spryker\Zed\ProductConfigurationCart\Business\Comparator\ItemComparatorInterface;
use Spryker\Zed\ProductConfigurationCart\Business\Counter\ProductConfigurationCartItemQuantityCounter;
use Spryker\Zed\ProductConfigurationCart\Business\Counter\ProductConfigurationCartItemQuantityCounterInterface;
use Spryker\Zed\ProductConfigurationCart\Business\Counter\ProductConfigurationItemQuantityCounter;
use Spryker\Zed\ProductConfigurationCart\Business\Counter\ProductConfigurationItemQuantityCounterInterface;
use Spryker\Zed\ProductConfigurationCart\Business\Expander\ProductConfigurationGroupKeyItemExpander;
use Spryker\Zed\ProductConfigurationCart\Business\Expander\ProductConfigurationGroupKeyItemExpanderInterface;
use Spryker\Zed\ProductConfigurationCart\Business\Expander\ProductConfigurationPriceProductExpander;
use Spryker\Zed\ProductConfigurationCart\Business\Expander\ProductConfigurationPriceProductExpanderInterface;
use Spryker\Zed\ProductConfigurationCart\Business\Validator\QuoteRequestProductConfigurationValidator;
use Spryker\Zed\ProductConfigurationCart\Business\Validator\QuoteRequestProductConfigurationValidatorInterface;
use Spryker\Zed\ProductConfigurationCart\Dependency\Service\ProductConfigurationCartToProductConfigurationServiceInterface;
use Spryker\Zed\ProductConfigurationCart\ProductConfigurationCartDependencyProvider;

/**
 * @method \Spryker\Zed\ProductConfigurationCart\ProductConfigurationCartConfig getConfig()
 */
class ProductConfigurationCartBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductConfigurationCart\Business\Expander\ProductConfigurationGroupKeyItemExpanderInterface
     */
    public function createProductConfigurationGroupKeyItemExpander(): ProductConfigurationGroupKeyItemExpanderInterface
    {
        return new ProductConfigurationGroupKeyItemExpander($this->getProductConfigurationService());
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationCart\Business\Checker\ProductConfigurationCheckerInterface
     */
    public function createProductConfigurationChecker(): ProductConfigurationCheckerInterface
    {
        return new ProductConfigurationChecker();
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationCart\Business\Expander\ProductConfigurationPriceProductExpanderInterface
     */
    public function createProductConfigurationPriceProductExpander(): ProductConfigurationPriceProductExpanderInterface
    {
        return new ProductConfigurationPriceProductExpander();
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationCart\Business\Counter\ProductConfigurationCartItemQuantityCounterInterface
     */
    public function createProductConfigurationCartItemQuantityCounter(): ProductConfigurationCartItemQuantityCounterInterface
    {
        return new ProductConfigurationCartItemQuantityCounter(
            $this->createItemComparator(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationCart\Business\Counter\ProductConfigurationItemQuantityCounterInterface
     */
    public function createProductConfigurationItemQuantityCounter(): ProductConfigurationItemQuantityCounterInterface
    {
        return new ProductConfigurationItemQuantityCounter(
            $this->createItemComparator(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationCart\Business\Comparator\ItemComparatorInterface
     */
    public function createItemComparator(): ItemComparatorInterface
    {
        return new ItemComparator(
            $this->getProductConfigurationService(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationCart\Business\Validator\QuoteRequestProductConfigurationValidatorInterface
     */
    public function createQuoteRequestProductConfigurationValidator(): QuoteRequestProductConfigurationValidatorInterface
    {
        return new QuoteRequestProductConfigurationValidator();
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationCart\Dependency\Service\ProductConfigurationCartToProductConfigurationServiceInterface
     */
    public function getProductConfigurationService(): ProductConfigurationCartToProductConfigurationServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationCartDependencyProvider::SERVICE_PRODUCT_CONFIGURATION);
    }
}
