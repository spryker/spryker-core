<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

/**
 * @method \Spryker\Glue\OrdersRestApi\OrdersRestApiFactory getFactory()
 */
class OrdersRestApiResource implements OrdersRestApiResourceInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $orderReference
     * @param string $customerReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findOrderByOrderReference(string $orderReference, string $customerReference): ?RestResourceInterface
    {
        return $this->getFactory()->createOrderReader()->getOrderByOrderReference($orderReference, $customerReference);
    }
}
