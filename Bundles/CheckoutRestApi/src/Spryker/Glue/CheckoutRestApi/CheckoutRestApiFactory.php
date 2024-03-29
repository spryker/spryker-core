<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi;

use Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutProcessor;
use Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutProcessorInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutResponseMapper;
use Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutResponseMapperInterface;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapper;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataReader;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataReaderInterface;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataResponseMapper\AddressCheckoutDataResponseMapper;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataResponseMapper\CheckoutDataResponseMapperInterface;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataResponseMapper\PaymentProviderCheckoutDataResponseMapper;
use Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataResponseMapper\ShipmentMethodCheckoutDataResponseMapper;
use Spryker\Glue\CheckoutRestApi\Processor\Customer\CustomerMapper;
use Spryker\Glue\CheckoutRestApi\Processor\Customer\CustomerMapperInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Error\RestCheckoutErrorMapper;
use Spryker\Glue\CheckoutRestApi\Processor\Error\RestCheckoutErrorMapperInterface;
use Spryker\Glue\CheckoutRestApi\Processor\RequestAttributesExpander\CheckoutRequestAttributesExpander;
use Spryker\Glue\CheckoutRestApi\Processor\RequestAttributesExpander\CheckoutRequestAttributesExpanderInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Validator\CheckoutRequestValidator;
use Spryker\Glue\CheckoutRestApi\Processor\Validator\CheckoutRequestValidatorInterface;
use Spryker\Glue\CheckoutRestApi\Processor\Validator\SinglePaymentValidator;
use Spryker\Glue\CheckoutRestApi\Processor\Validator\SinglePaymentValidatorInterface;
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
            $this->createCheckoutRequestAttributesExpander(),
            $this->createCheckoutRequestValidator(),
            $this->createRestCheckoutErrorMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataMapperInterface
     */
    public function createCheckoutDataMapper(): CheckoutDataMapperInterface
    {
        return new CheckoutDataMapper(
            $this->getCheckoutDataResponseMappers(),
            $this->getCheckoutDataResponseMapperPlugins(),
        );
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutProcessorInterface
     */
    public function createCheckoutProcessor(): CheckoutProcessorInterface
    {
        return new CheckoutProcessor(
            $this->getClient(),
            $this->getResourceBuilder(),
            $this->createCheckoutRequestAttributesExpander(),
            $this->createCheckoutRequestValidator(),
            $this->createRestCheckoutErrorMapper(),
            $this->createCheckoutResponseMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\RequestAttributesExpander\CheckoutRequestAttributesExpanderInterface
     */
    public function createCheckoutRequestAttributesExpander(): CheckoutRequestAttributesExpanderInterface
    {
        return new CheckoutRequestAttributesExpander(
            $this->createCustomerMapper(),
            $this->getConfig(),
            $this->getCheckoutRequestExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\Customer\CustomerMapperInterface
     */
    public function createCustomerMapper(): CustomerMapperInterface
    {
        return new CustomerMapper();
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\Checkout\CheckoutResponseMapperInterface
     */
    public function createCheckoutResponseMapper(): CheckoutResponseMapperInterface
    {
        return new CheckoutResponseMapper(
            $this->getCheckoutResponseMapperPlugins(),
        );
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\Validator\CheckoutRequestValidatorInterface
     */
    public function createCheckoutRequestValidator(): CheckoutRequestValidatorInterface
    {
        return new CheckoutRequestValidator(
            $this->getCheckoutRequestAttributesValidatorPlugins(),
            $this->getCheckoutRequestValidatorPlugins(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\Validator\SinglePaymentValidatorInterface
     */
    public function createSinglePaymentValidator(): SinglePaymentValidatorInterface
    {
        return new SinglePaymentValidator();
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\Error\RestCheckoutErrorMapperInterface
     */
    public function createRestCheckoutErrorMapper(): RestCheckoutErrorMapperInterface
    {
        return new RestCheckoutErrorMapper(
            $this->getConfig(),
            $this->getGlossaryStorageClient(),
        );
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Dependency\Client\CheckoutRestApiToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): CheckoutRestApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(CheckoutRestApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return array<\Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestAttributesValidatorPluginInterface>
     */
    public function getCheckoutRequestAttributesValidatorPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutRestApiDependencyProvider::PLUGINS_CHECKOUT_REQUEST_ATTRIBUTES_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestValidatorPluginInterface>
     */
    public function getCheckoutRequestValidatorPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutRestApiDependencyProvider::PLUGINS_CHECKOUT_REQUEST_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutResponseMapperPluginInterface>
     */
    public function getCheckoutResponseMapperPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutRestApiDependencyProvider::PLUGINS_CHECKOUT_RESPONSE_MAPPER);
    }

    /**
     * @return array<\Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataResponseMapperPluginInterface>
     */
    public function getCheckoutDataResponseMapperPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutRestApiDependencyProvider::PLUGINS_CHECKOUT_DATA_RESPONSE_MAPPER);
    }

    /**
     * @return array<\Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataResponseMapper\CheckoutDataResponseMapperInterface>
     */
    protected function getCheckoutDataResponseMappers(): array
    {
        return [
            $this->createAddressCheckoutDataResponseMapper(),
            $this->createPaymentProviderCheckoutDataResponseMapper(),
            $this->createShipmentMethodCheckoutDataResponseMapper(),
        ];
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataResponseMapper\CheckoutDataResponseMapperInterface
     */
    public function createAddressCheckoutDataResponseMapper(): CheckoutDataResponseMapperInterface
    {
        return new AddressCheckoutDataResponseMapper($this->getConfig());
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataResponseMapper\CheckoutDataResponseMapperInterface
     */
    public function createPaymentProviderCheckoutDataResponseMapper(): CheckoutDataResponseMapperInterface
    {
        return new PaymentProviderCheckoutDataResponseMapper($this->getConfig());
    }

    /**
     * @return \Spryker\Glue\CheckoutRestApi\Processor\CheckoutData\CheckoutDataResponseMapper\CheckoutDataResponseMapperInterface
     */
    public function createShipmentMethodCheckoutDataResponseMapper(): CheckoutDataResponseMapperInterface
    {
        return new ShipmentMethodCheckoutDataResponseMapper($this->getConfig());
    }

    /**
     * @return array<\Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestExpanderPluginInterface>
     */
    public function getCheckoutRequestExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutRestApiDependencyProvider::PLUGINS_CHECKOUT_REQUEST_EXPANDER);
    }
}
