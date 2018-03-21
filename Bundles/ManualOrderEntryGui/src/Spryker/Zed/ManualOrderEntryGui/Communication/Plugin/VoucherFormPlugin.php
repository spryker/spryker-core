<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class VoucherFormPlugin extends AbstractFormPlugin implements ManualOrderEntryFormPluginInterface
{
    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToDiscountFacadeInterface
     */
    protected $discountFacade;

    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToDiscountFacadeInterface $discountFacade
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMessengerFacadeInterface $messengerFacade
     */
    public function __construct($discountFacade, $messengerFacade)
    {
        $this->discountFacade = $discountFacade;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $dataTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(Request $request, $dataTransfer = null)
    {
        return $this->getFactory()->createVoucherForm($dataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handleData($quoteTransfer, &$form, $request)
    {
        if (strlen($quoteTransfer->getVoucherCode())) {
            $discountTransfer = new DiscountTransfer();
            $discountTransfer->setVoucherCode($quoteTransfer->getVoucherCode());

            $quoteTransfer->setVoucherDiscounts(new ArrayObject());
            $quoteTransfer->addVoucherDiscount($discountTransfer);

            $quoteTransfer = $this->discountFacade->calculateDiscounts($quoteTransfer);

            if (!count($quoteTransfer->getVoucherDiscounts())) {
                $this->addMessage(sprintf('Voucher code \'%s\' has not been applied', $quoteTransfer->getVoucherCode()), false);
                $quoteTransfer->setVoucherCode('');

                $form = $this->createForm($request, $quoteTransfer);
                $form->setData($quoteTransfer->toArray());
            } else {
                $this->addMessage(sprintf('Voucher code \'%s\' has been applied', $quoteTransfer->getVoucherCode()));
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param string $message
     * @param bool $isSuccess
     *
     * @return void
     */
    protected function addMessage($message, $isSuccess = true)
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($message);

        if ($isSuccess) {
            $this->messengerFacade->addSuccessMessage($messageTransfer);
        } else {
            $this->messengerFacade->addErrorMessage($messageTransfer);
        }
    }
}
