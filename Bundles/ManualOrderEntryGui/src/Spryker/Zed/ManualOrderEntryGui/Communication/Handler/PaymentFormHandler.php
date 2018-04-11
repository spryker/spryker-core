<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Handler;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToPaymentFacadeInterface;
use Symfony\Component\HttpFoundation\Request;

class PaymentFormHandler implements FormHandlerInterface
{
    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToPaymentFacadeInterface
     */
    protected $paymentFacade;

    /**
     * @var \Spryker\Zed\ManualOrderEntryGuiExtension\Dependency\Plugin\PaymentSubFormPluginInterface[]
     */
    protected $subFormPlugins;

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToPaymentFacadeInterface $paymentFacade
     * @param \Spryker\Zed\ManualOrderEntryGuiExtension\Dependency\Plugin\PaymentSubFormPluginInterface[] $subFormPlugins
     */
    public function __construct(
        ManualOrderEntryGuiToPaymentFacadeInterface $paymentFacade,
        $subFormPlugins
    ) {
        $this->paymentFacade = $paymentFacade;
        $this->subFormPlugins = $subFormPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handle(QuoteTransfer $quoteTransfer, &$form, Request $request): QuoteTransfer
    {
        $paymentSelection = $quoteTransfer->getPayment()->getPaymentSelection();

        foreach ($this->subFormPlugins as $subFormPlugin) {
            if ($paymentSelection == $subFormPlugin->getName()) {
                $quoteTransfer->getPayment()
                    ->setPaymentProvider($subFormPlugin->getPaymentProvider())
                    ->setPaymentMethod($subFormPlugin->getPaymentMethod());

                break;
            }
        }

        $calculableObjectTransfer = new CalculableObjectTransfer();
        $calculableObjectTransfer->setItems($quoteTransfer->getItems())
            ->setTotals($quoteTransfer->getTotals())
            ->setExpenses($quoteTransfer->getExpenses())
            ->setPriceMode($quoteTransfer->getPriceMode())
            ->setCurrencyIsoCode($quoteTransfer->getCurrency()->getCode())
            ->setVoucherDiscounts($quoteTransfer->getVoucherDiscounts())
            ->setCartRuleDiscounts($quoteTransfer->getCartRuleDiscounts())
            ->setOriginalQuote($quoteTransfer)
            ->setPromotionItems($quoteTransfer->getPromotionItems())
            ->setGiftCards($quoteTransfer->getGiftCards())
            ->setNotApplicableGiftCardCodes($quoteTransfer->getNotApplicableGiftCardCodes())
            ->setPayments($quoteTransfer->getPayments())
            ->setPayment($quoteTransfer->getPayment());

        if (count($calculableObjectTransfer->getItems())) {
            $this->paymentFacade->recalculatePayments($calculableObjectTransfer);
        }

        return $quoteTransfer;
    }
}
