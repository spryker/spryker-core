<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeCart\Business\Validator\Rule;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Zed\ShipmentTypeCart\Business\Validator\ErrorCreator\SalesShipmentTypeValidationErrorCreatorInterface;

class ShipmentTypesHaveRelationWithShipmentMethodsCheckoutValidationRule implements ShipmentTypeCheckoutValidationRuleInterface
{
    /**
     * @var \Spryker\Zed\ShipmentTypeCart\Business\Validator\ErrorCreator\SalesShipmentTypeValidationErrorCreatorInterface
     */
    protected SalesShipmentTypeValidationErrorCreatorInterface $salesShipmentTypeValidationErrorCreator;

    /**
     * @param \Spryker\Zed\ShipmentTypeCart\Business\Validator\ErrorCreator\SalesShipmentTypeValidationErrorCreatorInterface $salesShipmentTypeValidationErrorCreator
     */
    public function __construct(SalesShipmentTypeValidationErrorCreatorInterface $salesShipmentTypeValidationErrorCreator)
    {
        $this->salesShipmentTypeValidationErrorCreator = $salesShipmentTypeValidationErrorCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function isQuoteReadyForCheckout(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        $invalidShipmentTypeUuids = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$this->isShipmentTypeDataProvided($itemTransfer)) {
                continue;
            }

            $shipmentTypeTransfer = $itemTransfer->getShipmentOrFail()->getMethodOrFail()->getShipmentTypeOrFail();
            if (
                !$this->isShipmentTypeMatchShipmentMethod($itemTransfer, $shipmentTypeTransfer)
                && !isset($invalidShipmentTypeUuids[$shipmentTypeTransfer->getUuidOrFail()])
            ) {
                $checkoutResponseTransfer
                    ->setIsSuccess(false)
                    ->addError($this->salesShipmentTypeValidationErrorCreator->createCheckoutErrorTransfer($shipmentTypeTransfer));
                $invalidShipmentTypeUuids[$shipmentTypeTransfer->getUuidOrFail()] = $shipmentTypeTransfer->getUuidOrFail();
            }
        }

        return $invalidShipmentTypeUuids === [];
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isShipmentTypeDataProvided(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getShipment() !== null
            && $itemTransfer->getShipmentOrFail()->getMethod() !== null
            && $itemTransfer->getShipmentOrFail()->getMethodOrFail()->getShipmentType() !== null
            && $itemTransfer->getShipmentOrFail()->getShipmentTypeUuid() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return bool
     */
    protected function isShipmentTypeMatchShipmentMethod(ItemTransfer $itemTransfer, ShipmentTypeTransfer $shipmentTypeTransfer): bool
    {
        return $itemTransfer->getShipmentOrFail()->getShipmentTypeUuidOrFail() === $shipmentTypeTransfer->getUuidOrFail();
    }
}
