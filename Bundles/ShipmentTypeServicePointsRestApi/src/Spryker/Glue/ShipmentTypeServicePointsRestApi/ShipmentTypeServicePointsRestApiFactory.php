<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToServicePointStorageClientInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToShipmentTypeStorageClientInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToStoreClientInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Resource\ShipmentTypeServicePointsRestApiToServicePointsRestApiResourceInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Builder\ServiceTypeResourceBuilder;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Builder\ServiceTypeResourceBuilderInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Checker\RestCheckoutRequestAttributesChecker;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Checker\RestCheckoutRequestAttributesCheckerInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Creator\RestErrorMessageCreator;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Creator\RestErrorMessageCreatorInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Expander\MultiShipmentServicePointAddressExpander;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Expander\ServicePointAddressExpanderInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Expander\ShipmentTypeServiceTypeResourceRelationshipExpander;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Expander\ShipmentTypeServiceTypeResourceRelationshipExpanderInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Expander\SingleShipmentServicePointAddressExpander;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Extractor\RestCheckoutRequestAttributesExtractor;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Extractor\RestCheckoutRequestAttributesExtractorInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ServicePointReader;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ServicePointReaderInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ServiceTypeReader;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ServiceTypeReaderInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReader;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\StrategyResolver\ShipmentTypeServicePointStrategyResolver;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\StrategyResolver\ShipmentTypeServicePointStrategyResolverInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\MultiShipmentShipmentTypeServicePointValidator;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\MultiShippingAddressValidator;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\Rule\ShipmentTypeServicePoint\CustomerDataShipmentTypeServicePointValidatorRule;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\Rule\ShipmentTypeServicePoint\MultiShipmentServicePointShipmentTypeServicePointValidatorRule;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\Rule\ShipmentTypeServicePoint\ServicePointHasAddressShipmentTypeServicePointValidatorRule;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\Rule\ShipmentTypeServicePoint\ShipmentTypeServicePointValidatorRuleInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\Rule\ShipmentTypeServicePoint\SingleShipmentServicePointShipmentTypeServicePointValidatorRule;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\ShipmentTypeServicePointValidatorInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\ShippingAddressValidatorInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\SingleShipmentShipmentTypeServicePointValidator;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\SingleShippingAddressValidator;

/**
 * @method \Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig getConfig()
 */
class ShipmentTypeServicePointsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Expander\ShipmentTypeServiceTypeResourceRelationshipExpanderInterface
     */
    public function createShipmentTypeServiceTypeResourceRelationshipExpander(): ShipmentTypeServiceTypeResourceRelationshipExpanderInterface
    {
        return new ShipmentTypeServiceTypeResourceRelationshipExpander(
            $this->createServiceTypeReader(),
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Expander\ServicePointAddressExpanderInterface
     */
    public function createMultiShipmentServicePointAddressExpander(): ServicePointAddressExpanderInterface
    {
        return new MultiShipmentServicePointAddressExpander(
            $this->createShipmentTypeStorageReader(),
            $this->createServicePointReader(),
            $this->createRestCheckoutRequestAttributesExtractor(),
        );
    }

    /**
     * @deprecated Use {@link \Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiFactory::createMultiShipmentServicePointAddressExpander()} instead.
     *
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Expander\ServicePointAddressExpanderInterface
     */
    public function createSingleShipmentServicePointAddressExpander(): ServicePointAddressExpanderInterface
    {
        return new SingleShipmentServicePointAddressExpander(
            $this->createShipmentTypeStorageReader(),
            $this->createServicePointReader(),
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ServiceTypeReaderInterface
     */
    public function createServiceTypeReader(): ServiceTypeReaderInterface
    {
        return new ServiceTypeReader(
            $this->getServicePointsRestApiResource(),
            $this->createServiceTypeResourceBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ServicePointReaderInterface
     */
    public function createServicePointReader(): ServicePointReaderInterface
    {
        return new ServicePointReader(
            $this->getServicePointStorageClient(),
            $this->getStoreClient(),
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Builder\ServiceTypeResourceBuilderInterface
     */
    public function createServiceTypeResourceBuilder(): ServiceTypeResourceBuilderInterface
    {
        return new ServiceTypeResourceBuilder($this->getResourceBuilder());
    }

    /**
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\MultiShippingAddressValidator
     */
    public function createMultiShippingAddressValidator(): ShippingAddressValidatorInterface
    {
        return new MultiShippingAddressValidator(
            $this->createShipmentTypeStorageReader(),
            $this->createRestCheckoutRequestAttributesExtractor(),
            $this->createRestErrorMessageCreator(),
        );
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *             Use {@link \Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiFactory::createMultiShippingAddressValidator()} instead.
     *
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\ShippingAddressValidatorInterface
     */
    public function createSingleShippingAddressValidator(): ShippingAddressValidatorInterface
    {
        return new SingleShippingAddressValidator(
            $this->createRestErrorMessageCreator(),
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\ShipmentTypeServicePointValidatorInterface
     */
    public function createMultiShipmentShipmentTypeServicePointValidator(): ShipmentTypeServicePointValidatorInterface
    {
        $shipmentTypeServicePointValidatorRules = [
            $this->createMultiShipmentServicePointShipmentTypeServicePointValidatorRule(),
            $this->createServicePointHasAddressShipmentTypeServicePointValidatorRule(),
            $this->createCustomerDataShipmentTypeServicePointValidatorRule(),
        ];

        return new MultiShipmentShipmentTypeServicePointValidator(
            $this->createRestCheckoutRequestAttributesExtractor(),
            $shipmentTypeServicePointValidatorRules,
            $this->createShipmentTypeStorageReader(),
        );
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *              Use {@link \Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiFactory::createMultiShipmentShipmentTypeServicePointValidator()} instead.
     *
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\ShipmentTypeServicePointValidatorInterface
     */
    public function createSingleShipmentShipmentTypeServicePointValidator(): ShipmentTypeServicePointValidatorInterface
    {
        $shipmentTypeServicePointValidatorRules = [
            $this->createSingleShipmentServicePointShipmentTypeServicePointValidatorRule(),
            $this->createServicePointHasAddressShipmentTypeServicePointValidatorRule(),
            $this->createCustomerDataShipmentTypeServicePointValidatorRule(),
        ];

        return new SingleShipmentShipmentTypeServicePointValidator(
            $shipmentTypeServicePointValidatorRules,
            $this->createShipmentTypeStorageReader(),
        );
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *               Use {@link \Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiFactory::createMultiShipmentServicePointShipmentTypeServicePointValidatorRule()} instead.
     *
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\Rule\ShipmentTypeServicePoint\ShipmentTypeServicePointValidatorRuleInterface
     */
    public function createSingleShipmentServicePointShipmentTypeServicePointValidatorRule(): ShipmentTypeServicePointValidatorRuleInterface
    {
        return new SingleShipmentServicePointShipmentTypeServicePointValidatorRule(
            $this->createRestErrorMessageCreator(),
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\Rule\ShipmentTypeServicePoint\ShipmentTypeServicePointValidatorRuleInterface
     */
    public function createMultiShipmentServicePointShipmentTypeServicePointValidatorRule(): ShipmentTypeServicePointValidatorRuleInterface
    {
        return new MultiShipmentServicePointShipmentTypeServicePointValidatorRule(
            $this->createShipmentTypeStorageReader(),
            $this->createRestCheckoutRequestAttributesExtractor(),
            $this->createRestErrorMessageCreator(),
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\Rule\ShipmentTypeServicePoint\ShipmentTypeServicePointValidatorRuleInterface
     */
    public function createServicePointHasAddressShipmentTypeServicePointValidatorRule(): ShipmentTypeServicePointValidatorRuleInterface
    {
        return new ServicePointHasAddressShipmentTypeServicePointValidatorRule(
            $this->createServicePointReader(),
            $this->getStoreClient(),
            $this->createRestErrorMessageCreator(),
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\Rule\ShipmentTypeServicePoint\ShipmentTypeServicePointValidatorRuleInterface
     */
    public function createCustomerDataShipmentTypeServicePointValidatorRule(): ShipmentTypeServicePointValidatorRuleInterface
    {
        return new CustomerDataShipmentTypeServicePointValidatorRule(
            $this->createRestErrorMessageCreator(),
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader\ShipmentTypeStorageReaderInterface
     */
    public function createShipmentTypeStorageReader(): ShipmentTypeStorageReaderInterface
    {
        return new ShipmentTypeStorageReader(
            $this->getConfig(),
            $this->getStoreClient(),
            $this->getShipmentTypeStorageClient(),
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Checker\RestCheckoutRequestAttributesCheckerInterface
     */
    public function createRestCheckoutRequestAttributesChecker(): RestCheckoutRequestAttributesCheckerInterface
    {
        return new RestCheckoutRequestAttributesChecker(
            $this->createShipmentTypeStorageReader(),
            $this->createRestCheckoutRequestAttributesExtractor(),
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Extractor\RestCheckoutRequestAttributesExtractorInterface
     */
    public function createRestCheckoutRequestAttributesExtractor(): RestCheckoutRequestAttributesExtractorInterface
    {
        return new RestCheckoutRequestAttributesExtractor();
    }

    /**
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Creator\RestErrorMessageCreatorInterface
     */
    public function createRestErrorMessageCreator(): RestErrorMessageCreatorInterface
    {
        return new RestErrorMessageCreator();
    }

    /**
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToStoreClientInterface
     */
    public function getStoreClient(): ShipmentTypeServicePointsRestApiToStoreClientInterface
    {
        return $this->getProvidedDependency(ShipmentTypeServicePointsRestApiDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToShipmentTypeStorageClientInterface
     */
    public function getShipmentTypeStorageClient(): ShipmentTypeServicePointsRestApiToShipmentTypeStorageClientInterface
    {
        return $this->getProvidedDependency(ShipmentTypeServicePointsRestApiDependencyProvider::CLIENT_SHIPMENT_TYPE_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToServicePointStorageClientInterface
     */
    public function getServicePointStorageClient(): ShipmentTypeServicePointsRestApiToServicePointStorageClientInterface
    {
        return $this->getProvidedDependency(ShipmentTypeServicePointsRestApiDependencyProvider::CLIENT_SERVICE_POINT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Resource\ShipmentTypeServicePointsRestApiToServicePointsRestApiResourceInterface
     */
    public function getServicePointsRestApiResource(): ShipmentTypeServicePointsRestApiToServicePointsRestApiResourceInterface
    {
        return $this->getProvidedDependency(ShipmentTypeServicePointsRestApiDependencyProvider::RESOURCE_SERVICE_POINTS_REST_API);
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *             Use {@link \Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiFactory::createMultiShipmentServicePointAddressExpander()} instead.
     *
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\StrategyResolver\ShipmentTypeServicePointStrategyResolverInterface
     */
    public function createServicePointAddressExpanderStrategyResolver(): ShipmentTypeServicePointStrategyResolverInterface
    {
        $strategyContainer = [];
        $strategyContainer[ShipmentTypeServicePointStrategyResolver::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createSingleShipmentServicePointAddressExpander();
        };
        $strategyContainer[ShipmentTypeServicePointStrategyResolver::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createMultiShipmentServicePointAddressExpander();
        };

        return new ShipmentTypeServicePointStrategyResolver($strategyContainer);
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *             Use {@link \Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiFactory::createMultiShipmentShipmentTypeServicePointValidator()} instead.
     *
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\StrategyResolver\ShipmentTypeServicePointStrategyResolverInterface
     */
    public function createShipmentTypeServicePointValidatorStrategyResolver(): ShipmentTypeServicePointStrategyResolverInterface
    {
        $strategyContainer = [];
        $strategyContainer[ShipmentTypeServicePointStrategyResolver::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createSingleShipmentShipmentTypeServicePointValidator();
        };
        $strategyContainer[ShipmentTypeServicePointStrategyResolver::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createMultiShipmentShipmentTypeServicePointValidator();
        };

        return new ShipmentTypeServicePointStrategyResolver($strategyContainer);
    }
}
