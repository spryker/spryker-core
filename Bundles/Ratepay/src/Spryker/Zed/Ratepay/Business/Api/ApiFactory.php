<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Ratepay\Business\Api\Builder\BuilderFactory;
use Spryker\Zed\Ratepay\Business\Api\Constants as ApiConstants;
use Spryker\Zed\Ratepay\Business\Api\Model\Deliver\Confirm as DeliverConfirm;

use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation as PaymentCalculation;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Cancel as PaymentCancel;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Configuration as PaymentConfiguration;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Confirm as PaymentConfirm;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init as PaymentInit;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Refund as PaymentRefund;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request as PaymentRequest;
use Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactory;
use Spryker\Zed\Ratepay\Business\Api\Model\Service\Profile as ProfileRequest;

class ApiFactory extends AbstractBusinessFactory
{
    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Builder\BuilderFactory
     */
    protected $builderFactory;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Builder\BuilderFactory $builderFactory
     */
    public function __construct(BuilderFactory $builderFactory)
    {
        $this->builderFactory = $builderFactory;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactoryInterface
     */
    public function createRequestModelFactory()
    {
        $factory = (new RequestModelFactory())
            ->registerBuilder(ApiConstants::REQUEST_MODEL_PAYMENT_INIT, $this->createInitModel())
            ->registerBuilder(ApiConstants::REQUEST_MODEL_PAYMENT_REQUEST, $this->createPaymentRequestModel())
            ->registerBuilder(ApiConstants::REQUEST_MODEL_PAYMENT_CONFIRM, $this->createPaymentConfirmModel())
            ->registerBuilder(ApiConstants::REQUEST_MODEL_DELIVER_CONFIRM, $this->createDeliverConfirmModel())
            ->registerBuilder(ApiConstants::REQUEST_MODEL_PAYMENT_CANCEL, $this->cancelPayment())
            ->registerBuilder(ApiConstants::REQUEST_MODEL_PAYMENT_REFUND, $this->refundPayment())
            ->registerBuilder(ApiConstants::REQUEST_MODEL_CONFIGURATION_REQUEST, $this->configurationRequest())
            ->registerBuilder(ApiConstants::REQUEST_MODEL_CALCULATION_REQUEST, $this->calculationRequest())
            ->registerBuilder(ApiConstants::REQUEST_MODEL_PROFILE, $this->profileRequest());

        return $factory;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Init
     */
    protected function createInitModel()
    {
        return new PaymentInit(
            $this->builderFactory->createHead()
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request
     */
    protected function createPaymentRequestModel()
    {
        return new PaymentRequest(
            $this->builderFactory->createHead(),
            $this->builderFactory->createCustomer(),
            $this->builderFactory->createShoppingBasket(),
            $this->builderFactory->createPayment()
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Confirm
     */
    protected function createPaymentConfirmModel()
    {
        return new PaymentConfirm(
            $this->builderFactory->createHead()
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Deliver\Confirm
     */
    protected function createDeliverConfirmModel()
    {
        return new DeliverConfirm(
            $this->builderFactory->createHead(),
            $this->builderFactory->createShoppingBasket()
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Cancel
     */
    protected function cancelPayment()
    {
        return new PaymentCancel(
            $this->builderFactory->createHead(),
            $this->builderFactory->createShoppingBasket()
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Refund
     */
    protected function refundPayment()
    {
        return new PaymentRefund(
            $this->builderFactory->createHead(),
            $this->builderFactory->createShoppingBasket()
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Configuration
     */
    protected function configurationRequest()
    {
        return new PaymentConfiguration(
            $this->builderFactory->createHead()
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation
     */
    protected function calculationRequest()
    {
        return new PaymentCalculation(
            $this->builderFactory->createHead(),
            $this->builderFactory->createInstallmentCalculation()
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\Service\Profile
     */
    protected function profileRequest()
    {
        return new ProfileRequest(
            $this->builderFactory->createHead()
        );
    }
}
