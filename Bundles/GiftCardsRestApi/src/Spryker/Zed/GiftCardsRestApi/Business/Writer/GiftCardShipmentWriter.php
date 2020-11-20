<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardsRestApi\Business\Writer;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\GiftCardsRestApi\GiftCardsRestApiConfig;

class GiftCardShipmentWriter implements GiftCardShipmentWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addDefaultShipmentForGiftCards(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        if (!$restCheckoutRequestAttributesTransfer->getShipments()->count()) {
            return $quoteTransfer;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$this->isGiftCard($itemTransfer)) {
                continue;
            }

            $shipmentTransfer = (new ShipmentTransfer())
                ->setShipmentSelection(GiftCardsRestApiConfig::SHIPMENT_METHOD_NAME_NO_SHIPMENT);

            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isGiftCard(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getGiftCardMetadata() && $itemTransfer->getGiftCardMetadata()->getIsGiftCard();
    }
}
