<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\ShipmentGroup;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardCheckerInterface;
use Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardFilterInterface;
use Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardReaderInterface;

class ShipmentGroupMethodFilter implements ShipmentGroupMethodFilterInterface
{
    /**
     * @var \Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardFilterInterface
     */
    protected $allowedShipmentMethodGiftCardFilter;

    /**
     * @var \Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardFilterInterface
     */
    protected $disallowedShipmentMethodGiftCardFilter;

    /**
     * @var \Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardCheckerInterface
     */
    protected $shipmentMethodGiftCardChecker;

    /**
     * @var \Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardReaderInterface
     */
    protected $giftCardConfigReader;

    /**
     * @param \Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardFilterInterface $allowedShipmentMethodGiftCardFilter
     * @param \Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardFilterInterface $disallowedShipmentMethodGiftCardFilter
     * @param \Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardCheckerInterface $shipmentMethodGiftCardChecker
     * @param \Spryker\Zed\GiftCard\Business\ShipmentMethod\ShipmentMethodGiftCardReaderInterface $giftCardConfigReader
     */
    public function __construct(
        ShipmentMethodGiftCardFilterInterface $allowedShipmentMethodGiftCardFilter,
        ShipmentMethodGiftCardFilterInterface $disallowedShipmentMethodGiftCardFilter,
        ShipmentMethodGiftCardCheckerInterface $shipmentMethodGiftCardChecker,
        ShipmentMethodGiftCardReaderInterface $giftCardConfigReader
    ) {
        $this->allowedShipmentMethodGiftCardFilter = $allowedShipmentMethodGiftCardFilter;
        $this->disallowedShipmentMethodGiftCardFilter = $disallowedShipmentMethodGiftCardFilter;
        $this->shipmentMethodGiftCardChecker = $shipmentMethodGiftCardChecker;
        $this->giftCardConfigReader = $giftCardConfigReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethods
     */
    public function filterShipmentMethods(ShipmentGroupTransfer $shipmentGroupTransfer): ArrayObject
    {
        $giftCardOnlyShipmentMethods = $this->giftCardConfigReader->getGiftCardOnlyShipmentMethods();

        if ($this->shipmentMethodGiftCardChecker->containsOnlyGiftCardItems($shipmentGroupTransfer)) {
            return $this->allowedShipmentMethodGiftCardFilter
                ->filter($shipmentGroupTransfer->getAvailableShipmentMethods(), $giftCardOnlyShipmentMethods);
        }

        return $this->disallowedShipmentMethodGiftCardFilter
            ->filter($shipmentGroupTransfer->getAvailableShipmentMethods(), $giftCardOnlyShipmentMethods);
    }
}
