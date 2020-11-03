<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ShipmentsRestApi\Dependency\Service\ShipmentsRestApiToShipmentServiceInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentMethodByCheckoutDataExpander;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentMethodByCheckoutDataExpanderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentsByOrderResourceRelationshipExpander;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentsByOrderResourceRelationshipExpanderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderDetailsAttributesMapper;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderDetailsAttributesMapperInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderShipmentsMapper;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderShipmentsMapperInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodMapper;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodMapperInterface;
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
            $this->getResourceBuilder(),
            $this->createOrderShipmentsMapper(),
            $this->getShipmentService()
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Dependency\Service\ShipmentsRestApiToShipmentServiceInterface
     */
    public function getShipmentService(): ShipmentsRestApiToShipmentServiceInterface
    {
        return $this->getProvidedDependency(ShipmentsRestApiDependencyProvider::SERVICE_SHIPMENT);
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderShipmentsMapperInterface
     */
    public function createOrderShipmentsMapper(): OrderShipmentsMapperInterface
    {
        return new OrderShipmentsMapper();
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\OrderDetailsAttributesMapperInterface
     */
    public function createOrderDetailsAttributesMapper(): OrderDetailsAttributesMapperInterface
    {
        return new OrderDetailsAttributesMapper();
    }
}
