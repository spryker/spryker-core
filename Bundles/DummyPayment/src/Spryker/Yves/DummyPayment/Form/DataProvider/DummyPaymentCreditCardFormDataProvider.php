<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DummyPayment\Form\DataProvider;

use Generated\Shared\Transfer\DummyPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\DummyPayment\Form\CreditCardSubForm;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;

class DummyPaymentCreditCardFormDataProvider implements StepEngineFormDataProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getPayment() === null) {
            $paymentTransfer = new PaymentTransfer();
            $paymentTransfer->setDummyPayment(new DummyPaymentTransfer());
            $paymentTransfer->setDummyPaymentCreditCard(new DummyPaymentTransfer());

            $quoteTransfer->setPayment($paymentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string, array<int|string, string>>
     */
    public function getOptions(AbstractTransfer $quoteTransfer): array
    {
        return [
            CreditCardSubForm::OPTION_CARD_EXPIRES_CHOICES_MONTH => $this->getMonthChoices(),
            CreditCardSubForm::OPTION_CARD_EXPIRES_CHOICES_YEAR => $this->getYearChoices(),
        ];
    }

    /**
     * @return array<int|string, string>
     */
    protected function getMonthChoices(): array
    {
        return [
            '01' => '01',
            '02' => '02',
            '03' => '03',
            '04' => '04',
            '05' => '05',
            '06' => '06',
            '07' => '07',
            '08' => '08',
            '09' => '09',
            '10' => '10',
            '11' => '11',
            '12' => '12',
        ];
    }

    /**
     * @return array<int|string, string>
     */
    protected function getYearChoices(): array
    {
        $currentYear = date('Y');

        return [
            $currentYear => $currentYear,
            ++$currentYear => $currentYear,
            ++$currentYear => $currentYear,
            ++$currentYear => $currentYear,
            ++$currentYear => $currentYear,
        ];
    }
}
