<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Communication\Controller;

use Exception;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @method \Spryker\Zed\Checkout\Business\CheckoutFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    public const MESSAGE_PLACE_ORDER_ERROR = 'Order can not be processed';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrderAction(QuoteTransfer $quoteTransfer)
    {
        try {
            $checkoutResponseTransfer = $this->getFacade()->placeOrder($quoteTransfer);
        } catch (Exception $exception) {
            $checkoutResponseTransfer = $this->createCheckoutResponseWithPlaceOrderFailure($exception);
        } catch (Throwable $exception) {
            $checkoutResponseTransfer = $this->createCheckoutResponseWithPlaceOrderFailure($exception);
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function isOrderPlaceableAction(QuoteTransfer $quoteTransfer): CheckoutResponseTransfer
    {
        return $this->getFacade()->isOrderPlaceable($quoteTransfer);
    }

    /**
     * @param \Exception|\Throwable $exception
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function createCheckoutResponseWithPlaceOrderFailure($exception): CheckoutResponseTransfer
    {
        $checkoutErrorTransfer = (new CheckoutErrorTransfer())
            ->setErrorCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->setMessage(static::MESSAGE_PLACE_ORDER_ERROR);

        $checkoutResponseTransfer = (new CheckoutResponseTransfer())
            ->addError($checkoutErrorTransfer)
            ->setIsSuccess(false);

        ErrorLogger::getInstance()->log($exception);

        return $checkoutResponseTransfer;
    }
}
