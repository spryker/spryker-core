<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PaymentApp;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PaymentApp\Dependency\Client\PaymentAppToZedRequestClientInterface;
use Spryker\Client\PaymentApp\Zed\PaymentAppStub;
use Spryker\Client\PaymentApp\Zed\PaymentAppStubInterface;

class PaymentAppFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\PaymentApp\Zed\PaymentAppStubInterface
     */
    public function createPaymentAppStub(): PaymentAppStubInterface
    {
        return new PaymentAppStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\PaymentApp\Dependency\Client\PaymentAppToZedRequestClientInterface
     */
    public function getZedRequestClient(): PaymentAppToZedRequestClientInterface
    {
        return $this->getProvidedDependency(PaymentAppDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
