<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypesRestApi\Business\ErrorCreator;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;

class ShipmentTypeCheckoutErrorCreator implements ShipmentTypeCheckoutErrorCreatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_IS_NOT_AVAILABLE = 'shipment_types_rest_api.error.shipment_type_not_available';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_NAME = '%name%';

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    public function createShipmentTypeNotAvailableCheckoutErrorTransfer(ShipmentTypeTransfer $shipmentTypeTransfer): CheckoutErrorTransfer
    {
        return (new CheckoutErrorTransfer())
            ->setMessage(static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_IS_NOT_AVAILABLE)
            ->setParameters([
                static::ERROR_MESSAGE_PARAMETER_NAME => $shipmentTypeTransfer->getNameOrFail(),
            ]);
    }
}
