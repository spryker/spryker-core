<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Voucher\VoucherType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\Traits\UniqueFlashMessagesTrait;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class VoucherManualOrderEntryFormPlugin extends AbstractPlugin implements ManualOrderEntryFormPluginInterface
{
    use UniqueFlashMessagesTrait;

    protected const MESSAGE_ERROR = 'Voucher code \'%s\' has not been applied';
    protected const MESSAGE_SUCCESS = 'Voucher code \'%s\' has been applied';

    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMessengerFacadeInterface
     */
    protected $messengerFacade;

    public function __construct()
    {
        $this->cartFacade = $this->getFactory()->getCartFacade();
        $this->messengerFacade = $this->getFactory()->getMessengerFacade();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return VoucherType::TYPE_NAME;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $dataTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(Request $request, $dataTransfer = null): FormInterface
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
    public function handleData($quoteTransfer, &$form, $request): QuoteTransfer
    {
        if (strlen($quoteTransfer->getVoucherCode())) {
            $discountTransfer = new DiscountTransfer();
            $discountTransfer->setVoucherCode($quoteTransfer->getVoucherCode());

            $quoteTransfer->setVoucherDiscounts(new ArrayObject());
            $quoteTransfer->addVoucherDiscount($discountTransfer);

            if (count($quoteTransfer->getItems())) {
                $quoteTransfer = $this->cartFacade->reloadItems($quoteTransfer);
            }

            if (!count($quoteTransfer->getVoucherDiscounts())) {
                $this->addMessage(sprintf(static::MESSAGE_ERROR, $quoteTransfer->getVoucherCode()), false);
                $quoteTransfer->setVoucherCode('');

                $form = $this->createForm($request, $quoteTransfer);
                $form->setData($quoteTransfer->toArray());
            } else {
                $this->addMessage(sprintf(static::MESSAGE_SUCCESS, $quoteTransfer->getVoucherCode()));
            }

            $this->uniqueFlashMessages();
        }

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
