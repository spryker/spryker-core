<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToSalesClientInterface;
use Spryker\Glue\OrdersRestApi\Processor\Expander\OrderByOrderReferenceResourceRelationshipExpander;
use Spryker\Glue\OrdersRestApi\Processor\Expander\OrderByOrderReferenceResourceRelationshipExpanderInterface;
use Spryker\Glue\OrdersRestApi\Processor\Mapper\OrderResourceMapper;
use Spryker\Glue\OrdersRestApi\Processor\Mapper\OrderResourceMapperInterface;
use Spryker\Glue\OrdersRestApi\Processor\Mapper\OrderResourceShipmentMapper;
use Spryker\Glue\OrdersRestApi\Processor\Mapper\OrderResourceShipmentMapperInterface;
use Spryker\Glue\OrdersRestApi\Processor\Order\OrderReader;
use Spryker\Glue\OrdersRestApi\Processor\Order\OrderReaderInterface;

class OrdersRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\OrdersRestApi\Processor\Order\OrderReaderInterface
     */
    public function createOrderReader(): OrderReaderInterface
    {
        return new OrderReader(
            $this->getSalesClient(),
            $this->getResourceBuilder(),
            $this->createOrderResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\OrdersRestApi\Processor\Mapper\OrderResourceMapperInterface
     */
    public function createOrderResourceMapper(): OrderResourceMapperInterface
    {
        return new OrderResourceMapper($this->createOrderResourceShipmentMapper());
    }

    /**
     * @return \Spryker\Glue\OrdersRestApi\Processor\Mapper\OrderResourceShipmentMapperInterface
     */
    public function createOrderResourceShipmentMapper(): OrderResourceShipmentMapperInterface
    {
        return new OrderResourceShipmentMapper();
    }

    /**
     * @return \Spryker\Glue\OrdersRestApi\Processor\Expander\OrderByOrderReferenceResourceRelationshipExpanderInterface
     */
    public function createOrderByOrderReferenceResourceRelationshipExpander(): OrderByOrderReferenceResourceRelationshipExpanderInterface
    {
        return new OrderByOrderReferenceResourceRelationshipExpander($this->createOrderReader());
    }

    /**
     * @return \Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToSalesClientInterface
     */
    public function getSalesClient(): OrdersRestApiToSalesClientInterface
    {
        return $this->getProvidedDependency(OrdersRestApiDependencyProvider::CLIENT_SALES);
    }
}
