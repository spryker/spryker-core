<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesOrdersRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductBundlesOrdersRestApi\Dependency\RestResource\ProductBundlesOrdersRestApiToOrdersRestApiResourceInterface;
use Spryker\Glue\ProductBundlesOrdersRestApi\Processor\Mapper\OrderMapper;
use Spryker\Glue\ProductBundlesOrdersRestApi\Processor\Mapper\OrderMapperInterface;

class ProductBundlesOrdersRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductBundlesOrdersRestApi\Dependency\RestResource\ProductBundlesOrdersRestApiToOrdersRestApiResourceInterface
     */
    public function getOrdersRestApiResource(): ProductBundlesOrdersRestApiToOrdersRestApiResourceInterface
    {
        return $this->getProvidedDependency(ProductBundlesOrdersRestApiDependencyProvider::RESOURCE_ORDERS_REST_API);
    }

    /**
     * @return \Spryker\Glue\ProductBundlesOrdersRestApi\Processor\Mapper\OrderMapperInterface
     */
    public function createOrderMapper(): OrderMapperInterface
    {
        return new OrderMapper($this->getOrdersRestApiResource());
    }
}
