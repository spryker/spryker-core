<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart;

use Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface;
use Spryker\Client\ConfigurableBundleCart\Reader\QuoteItemReader;
use Spryker\Client\ConfigurableBundleCart\Reader\QuoteItemReaderInterface;
use Spryker\Client\ConfigurableBundleCart\Updater\QuoteItemUpdater;
use Spryker\Client\ConfigurableBundleCart\Updater\QuoteItemUpdaterInterface;
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
            $this->createQuoteItemReader(),
            $this->createQuoteItemUpdater()
        );
    }

    /**
     * @return \Spryker\Client\ConfigurableBundleCart\Updater\QuoteItemUpdaterInterface
     */
    public function createQuoteItemUpdater(): QuoteItemUpdaterInterface
    {
        return new QuoteItemUpdater(
            $this->createQuoteItemReader()
        );
    }

    /**
     * @return \Spryker\Client\ConfigurableBundleCart\Reader\QuoteItemReaderInterface
     */
    public function createQuoteItemReader(): QuoteItemReaderInterface
    {
        return new QuoteItemReader();
    }

    /**
     * @return \Spryker\Client\ConfigurableBundleCart\Dependency\Client\ConfigurableBundleCartToCartClientInterface
     */
    public function getCartClient(): ConfigurableBundleCartToCartClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleCartDependencyProvider::CLIENT_CART);
    }
}
