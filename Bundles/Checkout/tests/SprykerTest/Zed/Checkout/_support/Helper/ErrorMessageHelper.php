<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Checkout\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Checkout\Business\CheckoutFacadeInterface;
use Spryker\Zed\Messenger\Business\MessengerFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ErrorMessageHelper extends Module
{
    protected const CHECKOUT_ERROR_MESSAGE_TRANSFER_VALUE = 'CHECKOUT_ERROR_MESSAGE_TRANSFER_VALUE';
    protected const CHECKOUT_ERROR_MESSAGE_TRANSFER_PARAMETERS = ['testParameter' => 'testValue'];

    use LocatorHelperTrait;

    /**
     * @return \Spryker\Zed\Checkout\Business\CheckoutFacadeInterface
     */
    public function getCheckoutFacade(): CheckoutFacadeInterface
    {
        return $this->getLocator()->checkout()->facade();
    }

    /**
     * @return \Spryker\Zed\Messenger\Business\MessengerFacadeInterface
     */
    public function getMessengerFacade(): MessengerFacadeInterface
    {
        return $this->getLocator()->messenger()->facade();
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    public function getMessageTransfer(): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue(static::CHECKOUT_ERROR_MESSAGE_TRANSFER_VALUE)
            ->setParameters(static::CHECKOUT_ERROR_MESSAGE_TRANSFER_PARAMETERS);
    }

    /**
     * @return string[]
     */
    public function getStoredMessageValues(): array
    {
        return $this->getMessengerFacade()->getStoredMessages()
            ? $this->getMessengerFacade()->getStoredMessages()->getErrorMessages()
            : [];
    }
}
