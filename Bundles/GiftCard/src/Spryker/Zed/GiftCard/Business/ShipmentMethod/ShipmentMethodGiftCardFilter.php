<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\ShipmentMethod;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;

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
     * @param bool $checkOnlyNonGiftCardMethods
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function filterGiftCardShipmentMethods(
        ShipmentMethodsTransfer $shipmentMethodsTransfer,
        bool $checkOnlyNonGiftCardMethods
    ): ArrayObject {
        $shipmentMethodsTransferForRemoveIndexes = [];
        $giftCardOnlyShipmentMethods = $this->shipmentMethodGiftCardCollectionGetter->getGiftCardOnlyShipmentMethods();
        $shipmentMethodsTransferList = $shipmentMethodsTransfer->getMethods();

        foreach ($shipmentMethodsTransferList as $shipmentMethodIndex => $shipmentMethodTransfer) {
            if ($this->isShipmentMethodSuitable(
                $shipmentMethodTransfer,
                $giftCardOnlyShipmentMethods,
                $checkOnlyNonGiftCardMethods
            )) {
                $shipmentMethodsTransferForRemoveIndexes[] = $shipmentMethodIndex;
            }
        }

        return $this->shipmentMethodCollectionRemover->remove(
            $shipmentMethodsTransferList,
            $shipmentMethodsTransferForRemoveIndexes
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param string[] $giftCardOnlyShipmentMethods
     * @param bool $checkOnlyNonGiftCardMethods
     *
     * @return bool
     */
    protected function isShipmentMethodSuitable(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        array $giftCardOnlyShipmentMethods,
        bool $checkOnlyNonGiftCardMethods
    ): bool {
        if ($checkOnlyNonGiftCardMethods) {
            return in_array($shipmentMethodTransfer->getName(), $giftCardOnlyShipmentMethods);
        }

        return !in_array($shipmentMethodTransfer->getName(), $giftCardOnlyShipmentMethods);
    }
}
