<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Business;

use Spryker\Service\ProductConfiguration\ProductConfigurationServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductConfiguration\Business\Checker\ProductConfigurationChecker;
use Spryker\Zed\ProductConfiguration\Business\Checker\ProductConfigurationCheckerInterface;
use Spryker\Zed\ProductConfiguration\Business\Counter\Comparator\ItemComparator;
use Spryker\Zed\ProductConfiguration\Business\Counter\Comparator\ItemComparatorInterface;
use Spryker\Zed\ProductConfiguration\Business\Counter\ProductConfigurationCartItemQuantityCounter;
use Spryker\Zed\ProductConfiguration\Business\Counter\ProductConfigurationCartItemQuantityCounterInterface;
use Spryker\Zed\ProductConfiguration\Business\Counter\ProductConfigurationItemQuantityCounter;
use Spryker\Zed\ProductConfiguration\Business\Counter\ProductConfigurationItemQuantityCounterInterface;
use Spryker\Zed\ProductConfiguration\Business\Expander\ProductConfigurationGroupKeyItemExpander;
use Spryker\Zed\ProductConfiguration\Business\Expander\ProductConfigurationGroupKeyItemExpanderInterface;
use Spryker\Zed\ProductConfiguration\Business\Expander\ProductConfigurationPriceProductExpander;
use Spryker\Zed\ProductConfiguration\Business\Expander\ProductConfigurationPriceProductExpanderInterface;
use Spryker\Zed\ProductConfiguration\Business\Validator\QuoteRequestProductConfigurationValidator;
use Spryker\Zed\ProductConfiguration\Business\Validator\QuoteRequestProductConfigurationValidatorInterface;
use Spryker\Zed\ProductConfiguration\ProductConfigurationDependencyProvider;

/**
 * @method \Spryker\Zed\ProductConfiguration\Persistence\ProductConfigurationRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductConfiguration\ProductConfigurationConfig getConfig()
 */
class ProductConfigurationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductConfiguration\Business\Expander\ProductConfigurationGroupKeyItemExpanderInterface
     */
    public function createProductConfigurationGroupKeyItemExpander(): ProductConfigurationGroupKeyItemExpanderInterface
    {
        return new ProductConfigurationGroupKeyItemExpander($this->getProductConfigurationService());
    }

    /**
     * @return \Spryker\Zed\ProductConfiguration\Business\Checker\ProductConfigurationCheckerInterface
     */
    public function createProductConfigurationChecker(): ProductConfigurationCheckerInterface
    {
        return new ProductConfigurationChecker();
    }

    /**
     * @return \Spryker\Zed\ProductConfiguration\Business\Expander\ProductConfigurationPriceProductExpanderInterface
     */
    public function createProductConfigurationPriceProductExpander(): ProductConfigurationPriceProductExpanderInterface
    {
        return new ProductConfigurationPriceProductExpander();
    }

    /**
     * @return \Spryker\Zed\ProductConfiguration\Business\Counter\ProductConfigurationCartItemQuantityCounterInterface
     */
    public function createProductConfigurationCartItemQuantityCounter(): ProductConfigurationCartItemQuantityCounterInterface
    {
        return new ProductConfigurationCartItemQuantityCounter(
            $this->createItemComparator(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductConfiguration\Business\Counter\ProductConfigurationItemQuantityCounterInterface
     */
    public function createProductConfigurationItemQuantityCounter(): ProductConfigurationItemQuantityCounterInterface
    {
        return new ProductConfigurationItemQuantityCounter(
            $this->createItemComparator(),
        );
    }

    /**
     * @return \Spryker\Service\ProductConfiguration\ProductConfigurationServiceInterface
     */
    public function getProductConfigurationService(): ProductConfigurationServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::SERVICE_PRODUCT_CONFIGURATION);
    }

    /**
     * @return \Spryker\Zed\ProductConfiguration\Business\Counter\Comparator\ItemComparatorInterface
     */
    public function createItemComparator(): ItemComparatorInterface
    {
        return new ItemComparator(
            $this->getProductConfigurationService(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ProductConfiguration\Business\Validator\QuoteRequestProductConfigurationValidatorInterface
     */
    public function createQuoteRequestProductConfigurationValidator(): QuoteRequestProductConfigurationValidatorInterface
    {
        return new QuoteRequestProductConfigurationValidator();
    }
}
