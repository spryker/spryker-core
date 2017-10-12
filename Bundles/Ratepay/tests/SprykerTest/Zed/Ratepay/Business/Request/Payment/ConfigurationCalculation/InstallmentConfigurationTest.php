<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Request\Payment\ConfigurationCalculation;

use Generated\Shared\Transfer\RatepayRequestTransfer;
use Spryker\Zed\Ratepay\Business\Api\Builder\Head;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Configuration;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\ConfigurationResponse;
use SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\ConfigurationInstallmentAdapterMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Request
 * @group Payment
 * @group ConfigurationCalculation
 * @group InstallmentConfigurationTest
 * Add your own group annotations below this line
 */
class InstallmentConfigurationTest extends InstallmentAbstractTest
{
    /**
     * @return \SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\ConfigurationInstallmentAdapterMock
     */
    protected function getPaymentSuccessResponseAdapterMock()
    {
        return new ConfigurationInstallmentAdapterMock();
    }

    /**
     * @return \SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\ConfigurationInstallmentAdapterMock
     */
    protected function getPaymentFailureResponseAdapterMock()
    {
        return (new ConfigurationInstallmentAdapterMock())->expectFailure();
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\RatepayFacade $facade
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    protected function runFacadeMethod($facade)
    {
        return $facade->installmentConfiguration($this->quoteTransfer);
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Configuration
     */
    protected function getConfigurationRequest()
    {
        return new Configuration(
            new Head(new RatepayRequestTransfer())
        );
    }

    /**
     * @param \SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\AbstractAdapterMock $adapterMock
     * @param string $request
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Response\ConfigurationResponse
     */
    protected function sendRequest($adapterMock, $request)
    {
        return new ConfigurationResponse($adapterMock->sendRequest($request));
    }

    /**
     * @return void
     */
    protected function testResponseInstance()
    {
        $this->assertInstanceOf('\Generated\Shared\Transfer\RatepayInstallmentConfigurationResponseTransfer', $this->responseTransfer);
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\ConfigurationResponse $expectedResponse
     *
     * @return void
     */
    protected function convertResponseToTransfer($expectedResponse)
    {
        $this->expectedResponseTransfer = $this->converterFactory
            ->getInstallmentConfigurationResponseConverter($expectedResponse, $this->getConfigurationRequest())
            ->convert();
    }
}
