<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Mapper;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class MultiShipmentQuoteMapper extends AbstractQuoteMapper
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isMappingRequired(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getShipment() || !$itemTransfer->getShipmentOrFail()->getShippingAddress()) {
                continue;
            }

            if (
                $this->isShipmentMethodDataSet($itemTransfer->getShipmentOrFail())
                && !$this->isAddressCustomerDataSet($itemTransfer->getShipmentOrFail()->getShippingAddressOrFail())
            ) {
                return true;
            }
        }

        return false;
    }

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
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (
                !$this->isShipmentMethodApplicable(
                    $itemTransfer->getShipmentOrFail()->getMethodOrFail(),
                    $shipmentTypeTransfersIndexedByIdShipmentMethod,
                )
            ) {
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
}
