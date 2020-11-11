<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ShipmentsRestApi\Dependency\Service\ShipmentsRestApiToShipmentServiceInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentByCheckoutDataExpander;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentByCheckoutDataExpanderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentMethodByCheckoutDataExpander;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentMethodByCheckoutDataExpanderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentMethodByShipmentExpander;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentMethodByShipmentExpanderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentsByOrderResourceRelationshipExpander;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentsByOrderResourceRelationshipExpanderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Factory\ShipmentServiceFactory;
use Spryker\Glue\ShipmentsRestApi\Processor\Factory\ShipmentServiceFactoryInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderShipmentMapper;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderShipmentMapperInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMapper;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMapperInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodMapper;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodMapperInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\OrderShipmentRestResponseBuilder;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\OrderShipmentRestResponseBuilderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodRestResponseBuilder;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodRestResponseBuilderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodSorter;
use Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodSorterInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Validator\AddressSourceCheckoutDataValidator;
use Spryker\Glue\ShipmentsRestApi\Processor\Validator\AddressSourceCheckoutDataValidatorInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Validator\ShipmentCheckoutDataValidator;
use Spryker\Glue\ShipmentsRestApi\Processor\Validator\ShipmentCheckoutDataValidatorInterface;

class ShipmentsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentByCheckoutDataExpanderInterface
     */
    public function createShipmentByCheckoutDataExpander(): ShipmentByCheckoutDataExpanderInterface
    {
        return new ShipmentByCheckoutDataExpander(
            $this->getShipmentService(),
            $this->createShipmentMapper(),
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentMethodByCheckoutDataExpanderInterface
     */
    public function createShipmentMethodByCheckoutDataExpander(): ShipmentMethodByCheckoutDataExpanderInterface
    {
        return new ShipmentMethodByCheckoutDataExpander(
            $this->createShipmentMethodRestResponseBuilder(),
            $this->createShipmentMethodMapper(),
            $this->createShipmentMethodSorter()
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentMethodByShipmentExpanderInterface
     */
    public function createShipmentMethodByShipmentExpander(): ShipmentMethodByShipmentExpanderInterface
    {
        return new ShipmentMethodByShipmentExpander(
            $this->createShipmentMethodRestResponseBuilder(),
            $this->createShipmentMethodMapper(),
            $this->createShipmentMethodSorter()
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMapperInterface
     */
    public function createShipmentMapper(): ShipmentMapperInterface
    {
        return new ShipmentMapper();
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodMapperInterface
     */
    public function createShipmentMethodMapper(): ShipmentMethodMapperInterface
    {
        return new ShipmentMethodMapper();
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodRestResponseBuilderInterface
     */
    public function createShipmentMethodRestResponseBuilder(): ShipmentMethodRestResponseBuilderInterface
    {
        return new ShipmentMethodRestResponseBuilder($this->getResourceBuilder());
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodSorterInterface
     */
    public function createShipmentMethodSorter(): ShipmentMethodSorterInterface
    {
        return new ShipmentMethodSorter();
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Validator\ShipmentCheckoutDataValidatorInterface
     */
    public function createShipmentCheckoutDataValidator(): ShipmentCheckoutDataValidatorInterface
    {
        return new ShipmentCheckoutDataValidator();
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Validator\AddressSourceCheckoutDataValidatorInterface
     */
    public function createAddressSourceCheckoutDataValidator(): AddressSourceCheckoutDataValidatorInterface
    {
        return new AddressSourceCheckoutDataValidator($this->getAddressSourceCheckerPlugins());
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentsByOrderResourceRelationshipExpanderInterface
     */
    public function createShipmentsByOrderResourceRelationshipExpander(): ShipmentsByOrderResourceRelationshipExpanderInterface
    {
        return new ShipmentsByOrderResourceRelationshipExpander(
            $this->createOrderShipmentRestResponseBuilder(),
            $this->createShipmentServiceFactory()
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderShipmentMapperInterface
     */
    public function createOrderShipmentMapper(): OrderShipmentMapperInterface
    {
        return new OrderShipmentMapper();
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Factory\ShipmentServiceFactoryInterface
     */
    public function createShipmentServiceFactory(): ShipmentServiceFactoryInterface
    {
        return new ShipmentServiceFactory();
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\OrderShipmentRestResponseBuilderInterface
     */
    public function createOrderShipmentRestResponseBuilder(): OrderShipmentRestResponseBuilderInterface
    {
        return new OrderShipmentRestResponseBuilder(
            $this->createOrderShipmentMapper(),
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\AddressSourceCheckerPluginInterface[]
     */
    public function getAddressSourceCheckerPlugins(): array
    {
        return $this->getProvidedDependency(ShipmentsRestApiDependencyProvider::PLUGINS_ADDRESS_SOURCE_CHECKER);
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Dependency\Service\ShipmentsRestApiToShipmentServiceInterface
     */
    public function getShipmentService(): ShipmentsRestApiToShipmentServiceInterface
    {
        return $this->getProvidedDependency(ShipmentsRestApiDependencyProvider::SERVICE_SHIPMENT);
    }
}
