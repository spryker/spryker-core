<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Payment;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Payment\Dependency\External\PaymentToHttpClientAdapterInterface;
use Spryker\Client\Payment\Dependency\Service\PaymentToUtilEncodingServiceInterface;
use Spryker\Client\Payment\Executor\PaymentRequestExecutor;
use Spryker\Client\Payment\Executor\PaymentRequestExecutorInterface;
use Spryker\Client\Payment\Zed\PaymentStub;

class PaymentFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Payment\Zed\PaymentStubInterface
     */
    public function createZedStub()
    {
        return new PaymentStub(
            $this->getZedRequestClient(),
        );
    }

    /**
     * @return \Spryker\Client\Payment\Executor\PaymentRequestExecutorInterface
     */
    public function createPaymentRequestExecutor(): PaymentRequestExecutorInterface
    {
        return new PaymentRequestExecutor(
            $this->getUtilEncodingService(),
            $this->getHttpClient(),
        );
    }

    /**
     * @return \Spryker\Client\Payment\Dependency\Service\PaymentToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PaymentToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PaymentDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Client\Payment\Dependency\External\PaymentToHttpClientAdapterInterface
     */
    public function getHttpClient(): PaymentToHttpClientAdapterInterface
    {
        return $this->getProvidedDependency(PaymentDependencyProvider::CLIENT_HTTP);
    }

    /**
     * @return \Spryker\Client\Payment\Dependency\Client\PaymentToZedRequestClientInterface
     */
    protected function getZedRequestClient()
    {
        /** @var \Spryker\Client\Payment\Dependency\Client\PaymentToZedRequestClientInterface $zedStub */
        $zedStub = $this->getProvidedDependency(PaymentDependencyProvider::CLIENT_ZED_REQUEST);

        return $zedStub;
    }
}
