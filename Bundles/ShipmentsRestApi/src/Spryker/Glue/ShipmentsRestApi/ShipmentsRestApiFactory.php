<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentMethodByCheckoutDataExpander;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentMethodByCheckoutDataExpanderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentsByOrderResourceRelationshipExpander;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentsByOrderResourceRelationshipExpanderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Factory\ShipmentServiceFactory;
use Spryker\Glue\ShipmentsRestApi\Processor\Factory\ShipmentServiceFactoryInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderShipmentMapper;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderShipmentMapperInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodMapper;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodMapperInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\OrderShipmentRestResponseBuilder;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\OrderShipmentRestResponseBuilderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodRestResponseBuilder;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodRestResponseBuilderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodSorter;
use Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodSorterInterface;

class ShipmentsRestApiFactory extends AbstractFactory
{
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
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Factory\ShipmentServiceFactoryInterface
     */
    public function getShipmentService(): ShipmentServiceFactoryInterface
    {
        return $this->getProvidedDependency(ShipmentsRestApiDependencyProvider::SERVICE_SHIPMENT);
    }
}
