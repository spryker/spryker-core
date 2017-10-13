<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyPayment\Business;

use Spryker\Zed\DummyPayment\Business\Model\Payment\Refund;
use Spryker\Zed\DummyPayment\DummyPaymentDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\DummyPayment\DummyPaymentConfig getConfig()
 */
class DummyPaymentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\DummyPayment\Business\Model\Payment\RefundInterface
     */
    public function createRefund()
    {
        return new Refund(
            $this->getRefundFacade()
        );
    }

    /**
     * @return \Spryker\Zed\DummyPayment\Dependency\Facade\DummyPaymentToRefundInterface
     */
    protected function getRefundFacade()
    {
        return $this->getProvidedDependency(DummyPaymentDependencyProvider::FACADE_REFUND);
    }
}
