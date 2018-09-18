<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToSalesClientInterface;
use Spryker\Glue\OrdersRestApi\Processor\Mapper\OrdersResourceMapper;
use Spryker\Glue\OrdersRestApi\Processor\Mapper\OrdersResourceMapperInterface;
use Spryker\Glue\OrdersRestApi\Processor\Orders\OrdersReader;
use Spryker\Glue\OrdersRestApi\Processor\Orders\OrdersReaderInterface;

class OrdersRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\OrdersRestApi\Processor\Orders\OrdersReaderInterface
     */
    public function createOrdersReader(): OrdersReaderInterface
    {
        return new OrdersReader(
            $this->getSalesClient(),
            $this->getResourceBuilder(),
            $this->createOrdersResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\OrdersRestApi\Processor\Mapper\OrdersResourceMapperInterface
     */
    public function createOrdersResourceMapper(): OrdersResourceMapperInterface
    {
        return new OrdersResourceMapper();
    }

    /**
     * @return \Spryker\Glue\OrdersRestApi\Dependency\Client\OrdersRestApiToSalesClientInterface
     */
    public function getSalesClient(): OrdersRestApiToSalesClientInterface
    {
        return $this->getProvidedDependency(OrdersRestApiDependencyProvider::CLIENT_SALES);
    }
}
