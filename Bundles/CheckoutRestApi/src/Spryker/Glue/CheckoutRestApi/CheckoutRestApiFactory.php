<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi;

use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartsRestApiClientInterface;
use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutProcessor;
use Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutProcessorInterface;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapper;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataReader;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataReaderInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteMerger;
use Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteMergerInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\CheckoutRestApi\CheckoutRestApiClientInterface getClient()
 * @method \Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig getConfig()
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
            $this->getCartsRestApiClient(),
            $this->getResourceBuilder(),
            $this->createCheckoutDataMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface
     */
    public function createCheckoutDataMapper(): CheckoutDataMapperInterface
    {
        return new CheckoutDataMapper($this->getConfig());
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\Quote\QuoteMergerInterface
     */
    public function createQuoteMerger(): QuoteMergerInterface
    {
        return new QuoteMerger(
            $this->createCheckoutDataMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutProcessorInterface
     */
    public function createCheckoutProcessor(): CheckoutProcessorInterface
    {
        return new CheckoutProcessor(
            $this->getResourceBuilder(),
            $this->createQuoteMerger(),
            $this->getClient(),
            $this->getGlossaryStorageClient(),
            $this->getCartsRestApiClient()
        );
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToCartsRestApiClientInterface
     */
    public function getCartsRestApiClient(): CheckoutRestApiToCartsRestApiClientInterface
    {
        return $this->getProvidedDependency(CheckoutRestApiDependencyProvider::CLIENT_CARTS_REST_API);
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): CheckoutRestApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(CheckoutRestApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }
}
