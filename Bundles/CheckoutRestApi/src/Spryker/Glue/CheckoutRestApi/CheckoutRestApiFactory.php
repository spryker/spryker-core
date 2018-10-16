<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi;

use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartClientInterface;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCheckoutClientInterface;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCustomerClientInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutProcessor;
use Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutProcessorInterface;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapper;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataReader;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataReaderInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteProcessor;
use Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteProcessorInterface;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface getClient()
 */
class CheckoutRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataReaderInterface
     */
    public function createCheckoutDataReader(): CheckoutDataReaderInterface
    {
        return new CheckoutDataReader(
            $this->getClient(),
            $this->getResourceBuilder(),
            $this->createCheckoutDataMapper(),
            $this->getQuoteCollectionReaderPlugin()
        );
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface
     */
    public function createCheckoutDataMapper(): CheckoutDataMapperInterface
    {
        return new CheckoutDataMapper();
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteProcessorInterface
     */
    public function createQuoteProcessor(): QuoteProcessorInterface
    {
        return new QuoteProcessor(
            $this->getCartClient(),
            $this->getQuoteCollectionReaderPlugin(),
            $this->createCheckoutDataMapper(),
            $this->getCustomerClient()
        );
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutProcessorInterface
     */
    public function createCheckoutWriter(): CheckoutProcessorInterface
    {
        return new CheckoutProcessor(
            $this->getResourceBuilder(),
            $this->createQuoteProcessor(),
            $this->getCheckoutClient()
        );
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
     */
    public function getQuoteCollectionReaderPlugin(): QuoteCollectionReaderPluginInterface
    {
        return $this->getProvidedDependency(CheckoutRestApiDependencyProvider::PLUGIN_QUOTE_COLLECTION_READER);
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartClientInterface
     */
    public function getCartClient(): CheckoutRestApiToCartClientInterface
    {
        return $this->getProvidedDependency(CheckoutRestApiDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCheckoutClientInterface
     */
    public function getCheckoutClient(): CheckoutRestApiToCheckoutClientInterface
    {
        return $this->getProvidedDependency(CheckoutRestApiDependencyProvider::CLIENT_CHECKOUT);
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCustomerClientInterface
     */
    public function getCustomerClient(): CheckoutRestApiToCustomerClientInterface
    {
        return $this->getProvidedDependency(CheckoutRestApiDependencyProvider::CLIENT_CUSTOMER);
    }
}
