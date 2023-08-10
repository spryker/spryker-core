<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeCart\Business\Validator\ErrorCreator;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;

class SalesShipmentTypeValidationErrorCreator implements SalesShipmentTypeValidationErrorCreatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_CART_CHECKOUT_ERROR = 'shipment_type_cart.checkout.validation.error';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_NAME = '%name%';

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    public function createCheckoutErrorTransfer(ShipmentTypeTransfer $shipmentTypeTransfer): CheckoutErrorTransfer
    {
        return (new CheckoutErrorTransfer())
            ->setMessage(static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_CART_CHECKOUT_ERROR)
            ->setParameters([
                static::ERROR_MESSAGE_PARAMETER_NAME => $shipmentTypeTransfer->getNameOrFail(),
            ]);
    }
}
