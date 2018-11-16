<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi;

use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutProcessor;
use Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutProcessorInterface;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapper;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataReader;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataReaderInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Customer\CustomerExpander;
use Spryker\Glue\CheckoutRestApi\Processor\Customer\CustomerExpanderInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Customer\CustomerValidator;
use Spryker\Glue\CheckoutRestApi\Processor\Customer\CustomerValidatorInterface;
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
            $this->getResourceBuilder(),
            $this->createCheckoutDataMapper(),
            $this->createCustomerValidator(),
            $this->createCustomerExpander()
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
     * @return \Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutProcessorInterface
     */
    public function createCheckoutProcessor(): CheckoutProcessorInterface
    {
        return new CheckoutProcessor(
            $this->getResourceBuilder(),
            $this->getClient(),
            $this->getGlossaryStorageClient(),
            $this->createCustomerValidator(),
            $this->createCustomerExpander()
        );
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\Customer\CustomerValidatorInterface
     */
    public function createCustomerValidator(): CustomerValidatorInterface
    {
        return new CustomerValidator();
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\Customer\CustomerExpanderInterface
     */
    public function createCustomerExpander(): CustomerExpanderInterface
    {
        return new CustomerExpander();
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): CheckoutRestApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(CheckoutRestApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }
}
