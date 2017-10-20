<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Request\Payment\ConfigurationCalculation;

use Generated\Shared\Transfer\RatepayRequestTransfer;
use Spryker\Zed\Ratepay\Business\Api\Builder\Head;
use Spryker\Zed\Ratepay\Business\Api\Builder\InstallmentCalculation;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse;
use SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\CalculationByTimeInstallmentAdapterMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Request
 * @group Payment
 * @group ConfigurationCalculation
 * @group InstallmentCalculationByTimeTest
 * Add your own group annotations below this line
 */
class InstallmentCalculationByTimeTest extends InstallmentAbstractTest
{
    /**
     * @return \SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\CalculationByTimeInstallmentAdapterMock
     */
    protected function getPaymentSuccessResponseAdapterMock()
    {
        return new CalculationByTimeInstallmentAdapterMock();
    }

    /**
     * @return \SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\CalculationByTimeInstallmentAdapterMock
     */
    protected function getPaymentFailureResponseAdapterMock()
    {
        return (new CalculationByTimeInstallmentAdapterMock())->expectFailure();
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\RatepayFacade $facade
     *
     * @return \Generated\Shared\Transfer\RatepayResponseTransfer
     */
    protected function runFacadeMethod($facade)
    {
        return $facade->installmentCalculation($this->quoteTransfer);
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation
     */
    protected function getCalculationRequest()
    {
        $requestTransfer = new RatepayRequestTransfer();

        return new Calculation(
            new Head($requestTransfer),
            new InstallmentCalculation($requestTransfer)
        );
    }

    /**
     * @param \SprykerTest\Zed\Ratepay\Business\Api\Adapter\Http\AbstractAdapterMock $adapterMock
     * @param string $request
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse
     */
    protected function sendRequest($adapterMock, $request)
    {
        return new CalculationResponse($adapterMock->sendRequest($request));
    }

    /**
     * @return void
     */
    protected function testResponseInstance()
    {
        $this->assertInstanceOf('\Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer', $this->responseTransfer);
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse $expectedResponse
     *
     * @return void
     */
    protected function convertResponseToTransfer($expectedResponse)
    {
        $this->expectedResponseTransfer = $this->converterFactory
            ->getInstallmentCalculationResponseConverter($expectedResponse, $this->getCalculationRequest())
            ->convert();
    }
}
