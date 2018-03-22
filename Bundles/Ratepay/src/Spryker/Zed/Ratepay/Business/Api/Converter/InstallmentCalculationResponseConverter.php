<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Converter;

use Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer;
use Spryker\Zed\Ratepay\Business\Api\Constants;
use Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation;
use Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface;

class InstallmentCalculationResponseConverter extends BaseConverter
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
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\CalculationResponse $response
     * @param \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface $moneyFacade
     * @param \Spryker\Zed\Ratepay\Business\Api\Converter\TransferObjectConverter $responseTransfer
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Calculation $request
     */
    public function __construct(
        CalculationResponse $response,
        RatepayToMoneyInterface $moneyFacade,
        TransferObjectConverter $responseTransfer,
        Calculation $request
    ) {
        parent::__construct($response, $moneyFacade);

        $this->responseTransfer = $responseTransfer;
        $this->request = $request;
    }

    /**
     * @return \Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer
     */
    public function convert()
    {
        $baseResponse = $this->responseTransfer->convert();

        $responseTransfer = new RatepayInstallmentCalculationResponseTransfer();
        $responseTransfer
            ->setBaseResponse($baseResponse);

        $successCode = Constants::REQUEST_CODE_SUCCESS_MATRIX[Constants::REQUEST_MODEL_CALCULATION_REQUEST];
        if ($successCode == $baseResponse->getResultCode()) {
            $responseTransfer
                ->setTotalAmount($this->decimalToCents($this->response->getTotalAmount()))
                ->setAmount($this->decimalToCents($this->response->getAmount()))
                ->setInterestAmount($this->decimalToCents($this->response->getInterestAmount()))
                ->setServiceCharge($this->decimalToCents($this->response->getServiceCharge()))
                ->setInterestRate($this->decimalToCents($this->response->getInterestRate()))
                ->setAnnualPercentageRate($this->response->getAnnualPercentageRate())
                ->setMonthlyDebitInterest($this->decimalToCents($this->response->getMonthlyDebitInterest()))
                ->setRate($this->decimalToCents($this->response->getRate()))
                ->setNumberOfRates($this->response->getNumberOfRates())
                ->setLastRate($this->decimalToCents($this->response->getLastRate()))
                ->setPaymentFirstDay($this->response->getPaymentFirstday());
        }

        return $responseTransfer;
    }
}
