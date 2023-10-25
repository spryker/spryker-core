<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Mapper;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 *             Use {@link \Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Mapper\MultiShipmentQuoteMapper} instead.
 */
class SingleShipmentQuoteMapper extends AbstractQuoteMapper
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param array<int, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfersIndexedByIdShipmentMethod
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function expandShippingAddressWithCustomerData(
        QuoteTransfer $quoteTransfer,
        CustomerTransfer $customerTransfer,
        array $shipmentTypeTransfersIndexedByIdShipmentMethod
    ): QuoteTransfer {
        if (
            !$this->isShipmentMethodApplicable(
                $quoteTransfer->getShipmentOrFail()->getMethodOrFail(),
                $shipmentTypeTransfersIndexedByIdShipmentMethod,
            )
        ) {
            return $quoteTransfer;
        }

        $quoteTransfer->getShippingAddressOrFail()
            ->setFirstName($customerTransfer->getFirstNameOrFail())
            ->setLastName($customerTransfer->getLastNameOrFail())
            ->setSalutation($customerTransfer->getSalutationOrFail());

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getShipment() || !$itemTransfer->getShipmentOrFail()->getShippingAddress()) {
                continue;
            }

            $itemTransfer->getShipmentOrFail()
                ->getShippingAddressOrFail()
                ->setFirstName($customerTransfer->getFirstNameOrFail())
                ->setLastName($customerTransfer->getLastNameOrFail())
                ->setSalutation($customerTransfer->getSalutationOrFail());
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isMappingRequired(QuoteTransfer $quoteTransfer): bool
    {
        if (!$quoteTransfer->getShipment() || !$quoteTransfer->getShippingAddress()) {
            return false;
        }

        return $this->isShipmentMethodDataSet($quoteTransfer->getShipmentOrFail())
            && !$this->isAddressCustomerDataSet($quoteTransfer->getShippingAddressOrFail());
    }
}
