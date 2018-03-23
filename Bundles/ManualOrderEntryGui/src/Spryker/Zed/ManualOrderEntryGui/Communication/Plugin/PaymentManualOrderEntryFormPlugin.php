<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Plugin;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class PaymentManualOrderEntryFormPlugin extends AbstractManualOrderEntryFormPlugin implements ManualOrderEntryFormPluginInterface
{
    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToPaymentFacadeInterface
     */
    protected $paymentFacade;

    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\Payment\SubFormPluginInterface[]
     */
    protected $subFormPlugins;

    public function __construct()
    {
        $this->paymentFacade = $this->getFactory()->getPaymentFacade();
        $this->subFormPlugins = $this->getFactory()->getPaymentMethodSubFormPlugins();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $dataTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(Request $request, $dataTransfer = null)
    {
        return $this->getFactory()->createPaymentForm($request, $dataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function handleData($quoteTransfer, &$form, $request)
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
        $calculableObjectTransfer->setItems($quoteTransfer->getItems());
        $calculableObjectTransfer->setTotals($quoteTransfer->getTotals());
        $calculableObjectTransfer->setExpenses($quoteTransfer->getExpenses());
        $calculableObjectTransfer->setPriceMode($quoteTransfer->getPriceMode());
        $calculableObjectTransfer->setCurrencyIsoCode($quoteTransfer->getCurrency()->getCode());
        $calculableObjectTransfer->setVoucherDiscounts($quoteTransfer->getVoucherDiscounts());
        $calculableObjectTransfer->setCartRuleDiscounts($quoteTransfer->getCartRuleDiscounts());
        $calculableObjectTransfer->setOriginalQuote($quoteTransfer);
        $calculableObjectTransfer->setPromotionItems($quoteTransfer->getPromotionItems());
        $calculableObjectTransfer->setGiftCards($quoteTransfer->getGiftCards());
        $calculableObjectTransfer->setNotApplicableGiftCardCodes($quoteTransfer->getNotApplicableGiftCardCodes());
        $calculableObjectTransfer->setPayments($quoteTransfer->getPayments());
        $calculableObjectTransfer->setPayment($quoteTransfer->getPayment());

        $this->paymentFacade->recalculatePayments($calculableObjectTransfer);

        return $quoteTransfer;
    }
}
