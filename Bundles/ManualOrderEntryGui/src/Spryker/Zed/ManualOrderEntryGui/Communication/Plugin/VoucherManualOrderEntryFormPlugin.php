<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Plugin;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Voucher\VoucherType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\Traits\UniqueFlashMessagesTrait;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ManualOrderEntryGui\ManualOrderEntryGuiConfig getConfig()
 */
class VoucherManualOrderEntryFormPlugin extends AbstractPlugin implements ManualOrderEntryFormPluginInterface
{
    use UniqueFlashMessagesTrait;

    /**
     * @var string
     */
    protected const MESSAGE_ERROR = 'Voucher code \'%s\' has not been applied';

    /**
     * @var string
     */
    protected const MESSAGE_SUCCESS = 'Voucher code \'%s\' has been applied';

    /**
     * @api
     *
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMessengerFacadeInterface
     */
    protected $messengerFacade;

    public function __construct()
    {
        $this->messengerFacade = $this->getFactory()->getMessengerFacade();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return VoucherType::TYPE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(Request $request, QuoteTransfer $quoteTransfer): FormInterface
    {
        return $this->getFactory()->createVoucherForm($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handleData(QuoteTransfer $quoteTransfer, &$form, Request $request): QuoteTransfer
    {
        $voucherCode = $quoteTransfer->getManualOrder()->getVoucherCode();

        if ($voucherCode) {
            $quoteTransfer = $this->getFactory()
                ->createVoucherFormHandler()
                ->handle($quoteTransfer, $form, $request);

            $message = static::MESSAGE_SUCCESS;
            $isSuccessMessage = true;
            if (!count($quoteTransfer->getVoucherDiscounts())) {
                $message = static::MESSAGE_ERROR;
                $isSuccessMessage = false;
                $quoteTransfer->setVoucherCode('');

                $form = $this->createForm($request, $quoteTransfer);
                $form->setData($quoteTransfer->toArray());
            }
            $this->addMessage($message, ['%s' => $voucherCode], $isSuccessMessage);

            $this->uniqueFlashMessages();
        }

        return $quoteTransfer;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isFormPreFilled(QuoteTransfer $quoteTransfer): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isFormSkipped(Request $request, QuoteTransfer $quoteTransfer): bool
    {
        return false;
    }

    /**
     * @param string $message
     * @param array $parameters
     * @param bool $isSuccess
     *
     * @return void
     */
    protected function addMessage($message, $parameters = [], $isSuccess = true)
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($message);
        $messageTransfer->setParameters($parameters);

        if ($isSuccess) {
            $this->messengerFacade->addSuccessMessage($messageTransfer);
        } else {
            $this->messengerFacade->addErrorMessage($messageTransfer);
        }
    }
}
