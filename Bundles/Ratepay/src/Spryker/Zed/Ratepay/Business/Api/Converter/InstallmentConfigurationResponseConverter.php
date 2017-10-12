<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Converter;

use Generated\Shared\Transfer\RatepayInstallmentConfigurationResponseTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Configuration;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface;

class InstallmentConfigurationResponseConverter extends BaseConverter
{
    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation
     */
    protected $request;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Converter\TransferObjectConverter
     */
    protected $responseTransfer;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface $response
     * @param \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface $moneyFacade
     * @param \Spryker\Zed\Ratepay\Business\Api\Converter\TransferObjectConverter $responseTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Configuration $request
     */
    public function __construct(
        ResponseInterface $response,
        RatepayToMoneyInterface $moneyFacade,
        TransferObjectConverter $responseTransfer,
        Configuration $request
    ) {
        parent::__construct($response, $moneyFacade);

        $this->responseTransfer = $responseTransfer;
        $this->request = $request;
    }

    /**
     * @return \Generated\Shared\Transfer\RatepayInstallmentConfigurationResponseTransfer
     */
    public function convert()
    {
        $baseResponse = $this->responseTransfer->convert();

        $responseTransfer = new RatepayInstallmentConfigurationResponseTransfer();
        $responseTransfer
            ->setBaseResponse($baseResponse);

        $successCode = Constants::REQUEST_CODE_SUCCESS_MATRIX[Constants::REQUEST_MODEL_CONFIGURATION_REQUEST];
        if ($successCode == $baseResponse->getResultCode()) {
            $responseTransfer
                ->setInterestrateMin($this->response->getInterestrateMin())
                ->setInterestrateDefault($this->response->getInterestrateDefault())
                ->setInterestrateMax($this->response->getInterestrateMax())
                ->setInterestRateMerchantTowardsBank($this->response->getInterestRateMerchantTowardsBank())
                ->setMonthNumberMin($this->response->getMonthNumberMin())
                ->setMonthNumberMax($this->response->getMonthNumberMax())
                ->setMonthLongrun($this->response->getMonthLongrun())
                ->setAmountMinLongrun($this->response->getAmountMinLongrun())
                ->setMonthAllowed($this->response->getMonthAllowed())
                ->setValidPaymentFirstdays($this->response->getValidPaymentFirstdays())
                ->setPaymentFirstday($this->response->getPaymentFirstday())
                ->setPaymentAmount($this->response->getPaymentAmount())
                ->setPaymentLastrate($this->response->getPaymentLastrate())
                ->setRateMinNormal($this->response->getRateMinNormal())
                ->setRateMinLongrun($this->response->getRateMinLongrun())
                ->setServiceCharge($this->response->getServiceCharge())
                ->setMinDifferenceDueday($this->response->getMinDifferenceDueday());
        }

        return $responseTransfer;
    }
}
