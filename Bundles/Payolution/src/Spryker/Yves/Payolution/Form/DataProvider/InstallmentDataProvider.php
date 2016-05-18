<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Payolution\Form\DataProvider;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayolutionCalculationPaymentDetailTransfer;
use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\CartClientInterface;
use Spryker\Client\Payolution\PayolutionClientInterface;
use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Yves\Payolution\Form\InstallmentSubForm;
use Spryker\Yves\StepEngine\Dependency\DataProvider\DataProviderInterface;

class InstallmentDataProvider implements DataProviderInterface
{

    /**
     * @var \Spryker\Client\Payolution\PayolutionClientInterface
     */
    protected $payolutionClient;

    /**
     * @var \Spryker\Client\Cart\CartClientInterface
     */
    protected $cartClient;

    /**
     * @param \Spryker\Client\Payolution\PayolutionClientInterface $payolutionClient
     * @param \Spryker\Client\Cart\CartClientInterface $cartClient
     */
    public function __construct(PayolutionClientInterface $payolutionClient, CartClientInterface $cartClient)
    {
        $this->payolutionClient = $payolutionClient;
        $this->cartClient = $cartClient;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData()
    {
        $quoteTransfer = $this->getDataClass();

        if ($quoteTransfer->getPayment() === null) {
            $paymentTransfer = new PaymentTransfer();
            $paymentTransfer->setPayolution(new PayolutionPaymentTransfer());
            $paymentTransfer->setPayolutionInstallment(new PayolutionPaymentTransfer());
            $quoteTransfer->setPayment($paymentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            InstallmentSubForm::OPTION_INSTALLMENT_PAYMENT_DETAIL => $this->getInstallmentPaymentChoices(),
        ];
    }

    /**
     * @return array
     */
    protected function getInstallmentPaymentChoices()
    {
        $calculationResponseTransfer = $this->getInstallmentPayments();

        return $this->buildChoices($calculationResponseTransfer->getPaymentDetails());
    }

    /**
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    protected function getInstallmentPayments()
    {
        $quoteTransfer = $this->getDataClass();
        if ($this->payolutionClient->hasInstallmentPaymentsInSession()) {
            $calculationResponseTransfer = $this->payolutionClient->getInstallmentPaymentsFromSession();

            if ($this->isInstallmentPaymentsStillValid($quoteTransfer, $calculationResponseTransfer)) {
                return $calculationResponseTransfer;
            }
        }

        $calculationResponseTransfer = $this->payolutionClient->calculateInstallmentPayments($quoteTransfer);

        return $this->payolutionClient->storeInstallmentPaymentsInSession($calculationResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer $calculationResponseTransfer
     *
     * @return bool
     */
    protected function isInstallmentPaymentsStillValid(
        QuoteTransfer $quoteTransfer,
        PayolutionCalculationResponseTransfer $calculationResponseTransfer
    ) {
        if ($quoteTransfer->getTotals() === null) {
            return false;
        }

        return $quoteTransfer->getTotals()->getHash() === $calculationResponseTransfer->getTotalsAmountHash();
    }

    /**
     * @param \Generated\Shared\Transfer\PayolutionCalculationPaymentDetailTransfer[] $installmentPaymentDetails
     *
     * @return array
     */
    protected function buildChoices($installmentPaymentDetails)
    {
        $choices = [];
        foreach ($installmentPaymentDetails as $paymentDetail) {
            $choices[] = $this->buildChoice($paymentDetail);
        }

        return $choices;
    }

    /**
     * @param \Generated\Shared\Transfer\PayolutionCalculationPaymentDetailTransfer $paymentDetail
     *
     * @return string
     *
     * @todo: optimize format choices and add a Type for an installment choice
     */
    protected function buildChoice(PayolutionCalculationPaymentDetailTransfer $paymentDetail)
    {
        $choice =
            $paymentDetail->getCurrency() .
            $this->convertCentToDecimal($paymentDetail->getInstallments()[0]->getAmount()) .
            $paymentDetail->getDuration();

        return $choice;
    }

    /**
     * @param int $amount
     *
     * @return float
     */
    protected function convertCentToDecimal($amount)
    {
        return CurrencyManager::getInstance()->convertCentToDecimal($amount);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getDataClass()
    {
        return $this->cartClient->getQuote();
    }

}
