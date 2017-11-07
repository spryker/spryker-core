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
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\Checkout\Business\CheckoutFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    const MESSAGE_PLACE_ORDER_ERROR = 'Order can not be processed';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrderAction(QuoteTransfer $quoteTransfer)
    {
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        try {
            $checkoutResponseTransfer = $this->getFacade()->placeOrder($quoteTransfer, $checkoutResponseTransfer);
        } catch (Exception $exception) {
            $checkoutErrorTransfer = (new CheckoutErrorTransfer())
                ->setErrorCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->setMessage(static::MESSAGE_PLACE_ORDER_ERROR);

            $checkoutResponseTransfer
                ->addError($checkoutErrorTransfer)
                ->setIsSuccess(false);
        }

        return $checkoutResponseTransfer;
    }
}
