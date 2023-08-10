<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeCart\Business\Validator;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ShipmentTypeCart\Business\Reader\ShipmentTypeReaderInterface;
use Spryker\Zed\ShipmentTypeCart\Business\Validator\ErrorCreator\SalesShipmentTypeValidationErrorCreatorInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
class SingleShipmentShipmentTypeCheckoutValidator implements ShipmentTypeCheckoutValidatorInterface
{
    /**
     * @var \Spryker\Zed\ShipmentTypeCart\Business\Reader\ShipmentTypeReaderInterface
     */
    protected ShipmentTypeReaderInterface $shipmentTypeReader;

    /**
     * @var \Spryker\Zed\ShipmentTypeCart\Business\Validator\ErrorCreator\SalesShipmentTypeValidationErrorCreatorInterface
     */
    protected SalesShipmentTypeValidationErrorCreatorInterface $salesShipmentTypeValidationErrorCreator;

    /**
     * @param \Spryker\Zed\ShipmentTypeCart\Business\Reader\ShipmentTypeReaderInterface $shipmentTypeReader
     * @param \Spryker\Zed\ShipmentTypeCart\Business\Validator\ErrorCreator\SalesShipmentTypeValidationErrorCreatorInterface $salesShipmentTypeValidationErrorCreator
     */
    public function __construct(
        ShipmentTypeReaderInterface $shipmentTypeReader,
        SalesShipmentTypeValidationErrorCreatorInterface $salesShipmentTypeValidationErrorCreator
    ) {
        $this->shipmentTypeReader = $shipmentTypeReader;
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
        if (!$this->isShipmentTypeDataProvided($quoteTransfer)) {
            return true;
        }

        $shipmentTypeTransfer = $quoteTransfer->getShipmentOrFail()->getMethodOrFail()->getShipmentTypeOrFail();
        if ($quoteTransfer->getShipmentOrFail()->getShipmentTypeUuidOrFail() !== $shipmentTypeTransfer->getUuidOrFail()) {
            $checkoutResponseTransfer
                ->setIsSuccess(false)
                ->addError($this->salesShipmentTypeValidationErrorCreator->createCheckoutErrorTransfer($shipmentTypeTransfer));

            return false;
        }

        $shipmentTypeCollectionTransfer = $this->shipmentTypeReader->getActiveShipmentTypeCollection(
            [$shipmentTypeTransfer->getUuidOrFail()],
            $quoteTransfer->getStoreOrFail()->getNameOrFail(),
        );
        if ($shipmentTypeCollectionTransfer->getShipmentTypes()->count() !== 0) {
            return true;
        }

        $checkoutResponseTransfer
            ->setIsSuccess(false)
            ->addError($this->salesShipmentTypeValidationErrorCreator->createCheckoutErrorTransfer($shipmentTypeTransfer));

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isShipmentTypeDataProvided(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getShipment() !== null
            && $quoteTransfer->getShipmentOrFail()->getShipmentTypeUuid() !== null
            && $quoteTransfer->getShipmentOrFail()->getMethod() !== null
            && $quoteTransfer->getShipmentOrFail()->getMethodOrFail()->getShipmentType() !== null;
    }
}
