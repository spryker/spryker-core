<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentDetail\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesPaymentDetail\Business\MessageBroker\PaymentCreatedMessageHandler;
use Spryker\Zed\SalesPaymentDetail\Business\MessageBroker\PaymentCreatedMessageHandlerInterface;
use Spryker\Zed\SalesPaymentDetail\Business\MessageBroker\PaymentUpdatedMessageHandler;
use Spryker\Zed\SalesPaymentDetail\Business\MessageBroker\PaymentUpdatedMessageHandlerInterface;

/**
 * @method \Spryker\Zed\SalesPaymentDetail\SalesPaymentDetailConfig getConfig()
 * @method \Spryker\Zed\SalesPaymentDetail\Persistence\SalesPaymentDetailEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesPaymentDetail\Persistence\SalesPaymentDetailRepositoryInterface getRepository()
 */
class SalesPaymentDetailBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesPaymentDetail\Business\MessageBroker\PaymentCreatedMessageHandlerInterface
     */
    public function createPaymentCreatedMessageHandler(): PaymentCreatedMessageHandlerInterface
    {
        return new PaymentCreatedMessageHandler($this->getRepository(), $this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\SalesPaymentDetail\Business\MessageBroker\PaymentUpdatedMessageHandlerInterface
     */
    public function createPaymentUpdatedMessageHandler(): PaymentUpdatedMessageHandlerInterface
    {
        return new PaymentUpdatedMessageHandler($this->getRepository(), $this->getEntityManager());
    }
}
