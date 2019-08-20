<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentMethodsByCheckoutDataExpander;
use Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentMethodsByCheckoutDataExpanderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodsMapper;
use Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodsMapperInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodsRestResponseBuilder;
use Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodsRestResponseBuilderInterface;
use Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodsSorter;
use Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodsSorterInterface;

class ShipmentsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Expander\ShipmentMethodsByCheckoutDataExpanderInterface
     */
    public function createShipmentMethodsByCheckoutDataExpander(): ShipmentMethodsByCheckoutDataExpanderInterface
    {
        return new ShipmentMethodsByCheckoutDataExpander(
            $this->createShipmentMethodsRestResponseBuilder(),
            $this->createShipmentMethodsMapper(),
            $this->createShipmentMethodsSorter()
        );
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Mapper\ShipmentMethodsMapperInterface
     */
    public function createShipmentMethodsMapper(): ShipmentMethodsMapperInterface
    {
        return new ShipmentMethodsMapper();
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\RestResponseBuilder\ShipmentMethodsRestResponseBuilderInterface
     */
    public function createShipmentMethodsRestResponseBuilder(): ShipmentMethodsRestResponseBuilderInterface
    {
        return new ShipmentMethodsRestResponseBuilder($this->getResourceBuilder(), $this->createShipmentMethodsMapper());
    }

    /**
     * @return \Spryker\Glue\ShipmentsRestApi\Processor\Sorter\ShipmentMethodsSorterInterface
     */
    public function createShipmentMethodsSorter(): ShipmentMethodsSorterInterface
    {
        return new ShipmentMethodsSorter();
    }
}
