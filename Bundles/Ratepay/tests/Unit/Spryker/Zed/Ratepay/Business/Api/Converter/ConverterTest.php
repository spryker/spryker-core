<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Api\Converter;

use Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer;
use Generated\Shared\Transfer\RatepayInstallmentConfigurationResponseTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;
use Generated\Shared\Transfer\RatepayResponseTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\Money\Business\MoneyFacade;
use Spryker\Zed\Ratepay\Business\Api\Builder\Head;
use Spryker\Zed\Ratepay\Business\Api\Builder\InstallmentCalculation;
use Spryker\Zed\Ratepay\Business\Api\Converter\ConverterFactory;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Configuration;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\BaseResponse;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\ConfigurationResponse;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyBridge;
use Unit\Spryker\Zed\Ratepay\Business\Api\Response\Response;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Api
 * @group Converter
 * @group ConverterTest
 */
class ConverterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Converter\ConverterFactory
     */
    protected $converterFactory;

    /**
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    protected function setUp()
    {
        parent::setUp();

        $this->requestTransfer = new RatepayRequestTransfer();

        $ratepayToMoneyBridge = new RatepayToMoneyBridge(new MoneyFacade());
        $this->converterFactory = new ConverterFactory($ratepayToMoneyBridge);
    }

    public function testConverterData()
    {
        $responseTransfer = $this->getResponseTransferObject((new Response)->getTestPaymentConfirmResponseData());
        $this->doTestInstance($responseTransfer, RatepayResponseTransfer::class);
        $this->doTestConverterData($responseTransfer);

        $responseTransfer = $this->getResponseInstallmentConfigurationObject((new Response)->getTestConfigurationResponseData());
        $this->doTestInstance($responseTransfer, RatepayInstallmentConfigurationResponseTransfer::class);
        $this->doTestConverterData($responseTransfer->getBaseResponse());

        $responseTransfer = $this->getResponseInstallmentCalculationObject((new Response)->getTestCalculationResponseData());
        $this->doTestInstance($responseTransfer, RatepayInstallmentCalculationResponseTransfer::class);
        $this->doTestConverterData($responseTransfer->getBaseResponse());
    }

    /**
     * @param \Spryker\Shared\Transfer\AbstractTransfer $responseTransfer
     * @param string $className
     *
     * @return void
     */
    protected function doTestInstance($responseTransfer, $className)
    {
        $this->assertInstanceOf($className, $responseTransfer);
    }

    /**
     * @param \Spryker\Shared\Transfer\AbstractTransfer $responseTransfer
     *
     * @return void
     */
    protected function doTestConverterData($responseTransfer)
    {
        $this->assertEquals('OK', $responseTransfer->getStatusCode());
        $this->assertEquals('Successfully', $responseTransfer->getStatusText());

        $this->assertEquals(true, $responseTransfer->getSuccessful());
    }

    public function testResponseSuccessState()
    {
        $response = new Response;
        $successResponseTransfer = $this->getResponseTransferObject($response->getTestPaymentConfirmResponseData());
        $unSuccessResponseTransfer = $this->getResponseTransferObject($response->getTestPaymentConfirmUnsuccessResponseData());

        $this->assertEquals(true, $successResponseTransfer->getSuccessful());
        $this->assertNotEquals(true, $unSuccessResponseTransfer->getSuccessful());
    }

    /**
     * @param string $responseXml
     *
     * @return \Spryker\Shared\Transfer\AbstractTransfer
     */
    protected function getResponseTransferObject($responseXml)
    {
        $responseObject = new BaseResponse($responseXml);

        return $this->converterFactory
            ->getTransferObjectConverter($responseObject)
            ->convert();
    }

    /**
     * @param string $responseXml
     *
     * @return \Spryker\Shared\Transfer\AbstractTransfer
     */
    protected function getResponseInstallmentConfigurationObject($responseXml)
    {
        $responseObject = new ConfigurationResponse($responseXml);

        return $this->converterFactory
            ->getInstallmentConfigurationResponseConverter($responseObject, $this->getConfigurationRequest())
            ->convert();
    }

    /**
     * @param string $responseXml
     *
     * @return \Spryker\Shared\Transfer\AbstractTransfer
     */
    protected function getResponseInstallmentCalculationObject($responseXml)
    {
        $responseObject = new CalculationResponse($responseXml);

        return $this->converterFactory
            ->getInstallmentCalculationResponseConverter($responseObject, $this->getCalculationRequest())
            ->convert();
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Configuration
     */
    protected function getConfigurationRequest()
    {
        return new Configuration(
            new Head($this->requestTransfer)
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation
     */
    protected function getCalculationRequest()
    {
        return new Calculation(
            new Head($this->requestTransfer),
            new InstallmentCalculation($this->requestTransfer)
        );
    }

}
