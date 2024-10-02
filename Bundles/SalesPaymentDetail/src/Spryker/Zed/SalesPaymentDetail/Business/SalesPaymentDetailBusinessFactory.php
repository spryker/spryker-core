<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentDetail\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesPaymentDetail\Business\MessageBroker\PaymentMessageHandler;
use Spryker\Zed\SalesPaymentDetail\Business\MessageBroker\PaymentMessageHandlerInterface;

/**
 * @method \Spryker\Zed\SalesPaymentDetail\SalesPaymentDetailConfig getConfig()
 * @method \Spryker\Zed\SalesPaymentDetail\Persistence\SalesPaymentDetailEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesPaymentDetail\Persistence\SalesPaymentDetailRepositoryInterface getRepository()
 */
class SalesPaymentDetailBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesPaymentDetail\Business\MessageBroker\PaymentMessageHandlerInterface
     */
    public function createPaymentMessageHandler(): PaymentMessageHandlerInterface
    {
        return new PaymentMessageHandler($this->getRepository(), $this->getEntityManager());
    }
}
