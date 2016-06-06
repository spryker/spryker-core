<?php

/**
 * This file is part of the Spryker Platform.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\Payolution\Handler;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Payolution\PayolutionClientInterface;
use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Shared\Payolution\PayolutionConstants;
use Spryker\Yves\Payolution\Exception\PaymentMethodNotFoundException;
use Symfony\Component\HttpFoundation\Request;

class PayolutionHandler
{

    const PAYMENT_PROVIDER = 'payolution';

    /**
     * @var array
     */
    protected static $paymentMethods = [
        PaymentTransfer::PAYOLUTION_INVOICE => 'invoice',
        PaymentTransfer::PAYOLUTION_INSTALLMENT => 'installment',
    ];

    /**
     * @var array
     */
    protected static $payolutionPaymentMethodMapper = [
        PaymentTransfer::PAYOLUTION_INVOICE => PayolutionConstants::BRAND_INVOICE,
        PaymentTransfer::PAYOLUTION_INSTALLMENT => PayolutionConstants::BRAND_INSTALLMENT,
    ];

    /**
     * @var array
     */
    protected static $payolutionGenderMapper = [
        'Mr' => 'Male',
        'Mrs' => 'Female',
    ];

    /**
     * @var \Spryker\Client\Payolution\PayolutionClientInterface
     */
    protected $payolutionClient;

    /**
     * @var \Spryker\Shared\Library\Currency\CurrencyManager
     */
    protected $currencyManager;

    /**
     * @param \Spryker\Client\Payolution\PayolutionClientInterface $payolutionClient
     * @param \Spryker\Shared\Library\Currency\CurrencyManager $currencyManager
     */
    public function __construct(PayolutionClientInterface $payolutionClient, CurrencyManager $currencyManager)
    {
        $this->payolutionClient = $payolutionClient;
        $this->currencyManager = $currencyManager;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(Request $request, QuoteTransfer $quoteTransfer)
    {
        $paymentSelection = $quoteTransfer->getPayment()->getPaymentSelection();

        $this->setPaymentProviderAndMethod($quoteTransfer, $paymentSelection);
        $this->setPayolutionPayment($request, $quoteTransfer, $paymentSelection);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @return void
     */
    protected function setPaymentProviderAndMethod(QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $quoteTransfer->getPayment()
            ->setPaymentProvider(self::PAYMENT_PROVIDER)
            ->setPaymentMethod(self::$paymentMethods[$paymentSelection]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @return void
     */
    protected function setPayolutionPayment(Request $request, QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $payolutionPaymentTransfer = $this->getPayolutionPaymentTransfer($quoteTransfer, $paymentSelection);

        $billingAddress = $quoteTransfer->getBillingAddress();

        $payolutionPaymentTransfer
            ->setAccountBrand(self::$payolutionPaymentMethodMapper[$paymentSelection])
            ->setAddress($billingAddress)
            ->setGender(self::$payolutionGenderMapper[$billingAddress->getSalutation()])
            ->setEmail($quoteTransfer->getCustomer()->getEmail())
            ->setCurrencyIso3Code($this->getCurrency())
            ->setLanguageIso2Code($billingAddress->getIso2Code())
            ->setClientIp($request->getClientIp());

        if ($payolutionPaymentTransfer->getAccountBrand() === PayolutionConstants::BRAND_INSTALLMENT) {
            $this->setPayolutionInstallmentPayment($payolutionPaymentTransfer);
        }

        $quoteTransfer->getPayment()->setPayolution(clone $payolutionPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PayolutionPaymentTransfer $payolutionPaymentTransfer
     *
     * @return void
     */
    protected function setPayolutionInstallmentPayment(PayolutionPaymentTransfer $payolutionPaymentTransfer)
    {
        if ($this->payolutionClient->hasInstallmentPaymentsInSession() === false) {
            return;
        }

        $payolutionCalculationResponseTransfer = $this->payolutionClient->getInstallmentPaymentsFromSession();

        $installmentPaymentDetail = $payolutionCalculationResponseTransfer
            ->getPaymentDetails()[$payolutionPaymentTransfer->getInstallmentPaymentDetailIndex()];

        $payolutionPaymentTransfer
            ->setInstallmentCalculationId($payolutionCalculationResponseTransfer->getIdentificationUniqueid())
            ->setInstallmentAmount($installmentPaymentDetail->getInstallments()[0]->getAmount())
            ->setInstallmentDuration($installmentPaymentDetail->getDuration());
    }

    /**
     * @return string
     */
    protected function getCurrency()
    {
        return $this->currencyManager->getDefaultCurrency()->getIsoCode();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentSelection
     *
     * @throws \Spryker\Yves\Payolution\Exception\PaymentMethodNotFoundException
     *
     * @return \Generated\Shared\Transfer\PayolutionPaymentTransfer
     */
    protected function getPayolutionPaymentTransfer(QuoteTransfer $quoteTransfer, $paymentSelection)
    {
        $paymentMethod = ucfirst($paymentSelection);
        $method = 'get' . $paymentMethod;
        $paymentTransfer = $quoteTransfer->getPayment();
        if (!method_exists($paymentTransfer, $method) || ($quoteTransfer->getPayment()->$method() === null)) {
            throw new PaymentMethodNotFoundException(sprintf('Selected payment method "%s" not found in PaymentTransfer', $paymentMethod));
        }
        $payolutionPaymentTransfer = $quoteTransfer->getPayment()->$method();

        return $payolutionPaymentTransfer;
    }

}
