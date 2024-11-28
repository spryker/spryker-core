<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentAppShipment\Business;

use Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PaymentAppShipment\Business\PaymentAppShipmentBusinessFactory getFactory()
 */
class PaymentAppShipmentFacade extends AbstractFacade implements PaymentAppShipmentFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer
     */
    public function processExpressCheckoutPaymentRequest(
        ExpressCheckoutPaymentRequestTransfer $expressCheckoutPaymentRequestTransfer
    ): ExpressCheckoutPaymentResponseTransfer {
        return $this->getFactory()
            ->createExpressCheckoutPaymentRequestExecutor()
            ->processExpressCheckoutPaymentRequest($expressCheckoutPaymentRequestTransfer);
    }
}
