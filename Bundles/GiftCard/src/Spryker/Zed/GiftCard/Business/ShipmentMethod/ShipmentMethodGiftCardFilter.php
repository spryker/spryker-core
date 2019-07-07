<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\ShipmentMethod;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;

class ShipmentMethodGiftCardFilter implements ShipmentMethodGiftCardFilterInterface
{
    /**
     * @var \Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodCollectionRemoverInterface
     */
    protected $shipmentMethodCollectionRemover;

    /**
     * @var \Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardAllowanceCheckerInterface
     */
    protected $shipmentMethodGiftCardAllowanceChecker;

    /**
     * @param \Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodCollectionRemoverInterface $shipmentMethodCollectionRemover
     * @param \Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardAllowanceCheckerInterface $shipmentMethodGiftCardAllowanceChecker
     */
    public function __construct(
        ShipmentMethodCollectionRemoverInterface $shipmentMethodCollectionRemover,
        ShipmentMethodGiftCardAllowanceCheckerInterface $shipmentMethodGiftCardAllowanceChecker
    ) {
        $this->shipmentMethodCollectionRemover = $shipmentMethodCollectionRemover;
        $this->shipmentMethodGiftCardAllowanceChecker = $shipmentMethodGiftCardAllowanceChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     * @param string[] $giftCardOnlyShipmentMethods
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function filter(ShipmentMethodsTransfer $shipmentMethodsTransfer, array $giftCardOnlyShipmentMethods): ArrayObject
    {
        $shipmentMethodsTransferForRemoveIndexes = [];
        $shipmentMethodsTransferList = $shipmentMethodsTransfer->getMethods();

        foreach ($shipmentMethodsTransferList as $shipmentMethodIndex => $shipmentMethodTransfer) {
            if ($this->shipmentMethodGiftCardAllowanceChecker->isShipmentMethodSuitable(
                $shipmentMethodTransfer,
                $giftCardOnlyShipmentMethods
            )) {
                continue;
            }

            $shipmentMethodsTransferForRemoveIndexes[] = $shipmentMethodIndex;
        }

        return $this->shipmentMethodCollectionRemover->remove(
            $shipmentMethodsTransferList,
            $shipmentMethodsTransferForRemoveIndexes
        );
    }
}
