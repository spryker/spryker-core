<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Plugin;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Payment\PaymentType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\Traits\UniqueFlashMessagesTrait;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class PaymentManualOrderEntryFormPlugin extends AbstractPlugin implements ManualOrderEntryFormPluginInterface
{
    use UniqueFlashMessagesTrait;

    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToPaymentFacadeInterface
     */
    protected $paymentFacade;

    /**
     * @var \Spryker\Zed\ManualOrderEntryGuiExtension\Dependency\Plugin\PaymentSubFormPluginInterface[]
     */
    protected $subFormPlugins;

    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMessengerFacadeInterface
     */
    protected $messengerFacade;

    public function __construct()
    {
        $this->paymentFacade = $this->getFactory()->getPaymentFacade();
        $this->subFormPlugins = $this->getFactory()->getPaymentMethodSubFormPlugins();
        $this->messengerFacade = $this->getFactory()->getMessengerFacade();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return PaymentType::TYPE_NAME;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $dataTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(Request $request, $dataTransfer = null): FormInterface
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
    public function handleData($quoteTransfer, &$form, Request $request): AbstractTransfer
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

        $this->uniqueFlashMessages();

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $dataTransfer
     *
     * @return bool
     */
    public function isPreFilled($dataTransfer = null): bool
    {
        return false;
    }
}
