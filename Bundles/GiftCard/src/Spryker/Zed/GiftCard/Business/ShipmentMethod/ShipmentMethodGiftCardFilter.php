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
     * @var \Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardCollectionGetterInterface
     */
    protected $shipmentMethodGiftCardCollectionGetter;

    /**
     * @param \Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodCollectionRemoverInterface $shipmentMethodCollectionRemover
     * @param \Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardCollectionGetterInterface $shipmentMethodGiftCardCollectionGetter
     */
    public function __construct(
        ShipmentMethodCollectionRemoverInterface $shipmentMethodCollectionRemover,
        ShipmentMethodGiftCardCollectionGetterInterface $shipmentMethodGiftCardCollectionGetter
    ) {
        $this->shipmentMethodCollectionRemover = $shipmentMethodCollectionRemover;
        $this->shipmentMethodGiftCardCollectionGetter = $shipmentMethodGiftCardCollectionGetter;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function filterNonGiftCardShipmentMethods(ShipmentMethodsTransfer $shipmentMethodsTransfer): ArrayObject
    {
        $shipmentMethodsTransferForRemoveIndexes = [];
        $giftCardOnlyShipmentMethods = $this->shipmentMethodGiftCardCollectionGetter->getGiftCardOnlyShipmentMethods();
        $shipmentMethodsTransferList = $shipmentMethodsTransfer->getMethods();
        foreach ($shipmentMethodsTransferList as $shipmentMethodIndex => $shipmentMethodTransfer) {
            if (in_array($shipmentMethodTransfer->getName(), $giftCardOnlyShipmentMethods)) {
                $shipmentMethodsTransferForRemoveIndexes[] = $shipmentMethodIndex;
            }
        }

        return $this->shipmentMethodCollectionRemover->remove(
            $shipmentMethodsTransferList,
            $shipmentMethodsTransferForRemoveIndexes
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function filterGiftCardOnlyShipmentMethods(ShipmentMethodsTransfer $shipmentMethodsTransfer): ArrayObject
    {
        $shipmentMethodsTransferForRemoveIndexes = [];
        $giftCardOnlyShipmentMethods = $this->shipmentMethodGiftCardCollectionGetter->getGiftCardOnlyShipmentMethods();
        $shipmentMethodsTransferList = $shipmentMethodsTransfer->getMethods();
        foreach ($shipmentMethodsTransferList as $shipmentMethodIndex => $shipmentMethodTransfer) {
            if (!in_array($shipmentMethodTransfer->getName(), $giftCardOnlyShipmentMethods)) {
                $shipmentMethodsTransferForRemoveIndexes[] = $shipmentMethodIndex;
            }
        }

        return $this->shipmentMethodCollectionRemover->remove(
            $shipmentMethodsTransferList,
            $shipmentMethodsTransferForRemoveIndexes
        );
    }
}
