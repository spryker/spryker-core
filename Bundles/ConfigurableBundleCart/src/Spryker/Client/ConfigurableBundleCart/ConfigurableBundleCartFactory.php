<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart;

use Spryker\Client\ConfigurableBundleCart\Calculator\ItemsQuantityCalculator;
use Spryker\Client\ConfigurableBundleCart\Calculator\ItemsQuantityCalculatorInterface;
use Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface;
use Spryker\Client\ConfigurableBundleCart\Reader\QuoteReader;
use Spryker\Client\ConfigurableBundleCart\Reader\QuoteReaderInterface;
use Spryker\Client\ConfigurableBundleCart\Writer\CartWriter;
use Spryker\Client\ConfigurableBundleCart\Writer\CartWriterInterface;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\ConfigurableBundleCart\ConfigurableBundleCartConfig getConfig()
 */
class ConfigurableBundleCartFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ConfigurableBundleCart\Writer\CartWriterInterface
     */
    public function createCartWriter(): CartWriterInterface
    {
        return new CartWriter(
            $this->getCartClient(),
            $this->createQuoteReader(),
            $this->createItemsQuantityCalculator()
        );
    }

    /**
     * @return \Spryker\Client\ConfigurableBundleCart\Calculator\ItemsQuantityCalculatorInterface
     */
    public function createItemsQuantityCalculator(): ItemsQuantityCalculatorInterface
    {
        return new ItemsQuantityCalculator();
    }

    /**
     * @return \Spryker\Client\ConfigurableBundleCart\Reader\QuoteReaderInterface
     */
    public function createQuoteReader(): QuoteReaderInterface
    {
        return new QuoteReader();
    }

    /**
     * @return \Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface
     */
    public function getCartClient(): ConfigurableBundleCartToCartClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleCartDependencyProvider::CLIENT_CART);
    }
}
