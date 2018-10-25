<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Checkout;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface;

class CheckoutResponseTransferTray implements DiscountToMessengerInterface, CheckoutResponseTransferTrayInterface
{
    /**
     * @var \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected static $checkoutResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     */
    public function __construct(CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        static::$checkoutResponseTransfer = $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $message
     *
     * @return void
     */
    public function addSuccessMessage(MessageTransfer $message): void
    {
        $this->addErrorMessage($message);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $message
     *
     * @return void
     */
    public function addInfoMessage(MessageTransfer $message): void
    {
        $this->addErrorMessage($message);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $message
     *
     * @return void
     */
    public function addErrorMessage(MessageTransfer $message): void
    {
        $checkoutErrorTransfer = (new CheckoutErrorTransfer())
            ->setMessage($message->getValue())
            ->setErrorCode(301);

        static::$checkoutResponseTransfer
            ->addError($checkoutErrorTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function getCheckoutResponseTransfer(): CheckoutResponseTransfer
    {
        return self::$checkoutResponseTransfer;
    }
}
