<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderPaymentsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\OrderPaymentsRestApi\Processor\OrderPayment\OrderPaymentMapper;
use Spryker\Glue\OrderPaymentsRestApi\Processor\OrderPayment\OrderPaymentMapperInterface;
use Spryker\Glue\OrderPaymentsRestApi\Processor\OrderPayment\OrderPaymentUpdater;
use Spryker\Glue\OrderPaymentsRestApi\Processor\OrderPayment\OrderPaymentUpdaterInterface;
use Spryker\Glue\OrderPaymentsRestApi\Processor\RestResponseBuilder\OrderPaymentRestResponseBuilder;
use Spryker\Glue\OrderPaymentsRestApi\Processor\RestResponseBuilder\OrderPaymentRestResponseBuilderInterface;

/**
 * @method \Spryker\Client\OrderPaymentsRestApi\OrderPaymentsRestApiClientInterface getClient()
 * @method \Spryker\Glue\OrderPaymentsRestApi\OrderPaymentsRestApiConfig getConfig()
 */
class OrderPaymentsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\OrderPaymentsRestApi\Processor\OrderPayment\OrderPaymentUpdaterInterface
     */
    public function createOrderPaymentUpdater(): OrderPaymentUpdaterInterface
    {
        return new OrderPaymentUpdater(
            $this->createOrderPaymentRestResponseBuilder(),
            $this->getClient(),
            $this->createOrderPaymentMapper()
        );
    }

    /**
     * @return \Spryker\Glue\OrderPaymentsRestApi\Processor\OrderPayment\OrderPaymentMapperInterface
     */
    public function createOrderPaymentMapper(): OrderPaymentMapperInterface
    {
        return new OrderPaymentMapper();
    }

    /**
     * @return \Spryker\Glue\OrderPaymentsRestApi\Processor\RestResponseBuilder\OrderPaymentRestResponseBuilderInterface
     */
    public function createOrderPaymentRestResponseBuilder(): OrderPaymentRestResponseBuilderInterface
    {
        return new OrderPaymentRestResponseBuilder($this->getResourceBuilder());
    }
}
