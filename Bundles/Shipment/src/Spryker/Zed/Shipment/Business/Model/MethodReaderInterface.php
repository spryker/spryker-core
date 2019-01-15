<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;

interface MethodReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     *
     * @return int
     */
    public function create(ShipmentMethodTransfer $methodTransfer): int;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function getAvailableMethodsByShipment(QuoteTransfer $quoteTransfer): ArrayObject;

    /**
     * @param int $idMethod
     *
     * @return bool
     */
    public function hasMethod($idMethod): bool;

    /**
     * @param int $idMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function getShipmentMethodTransferById($idMethod): ShipmentMethodTransfer;

    /**
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodTransferById($idShipmentMethod): ?ShipmentMethodTransfer;

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function getShipmentMethodTransfers(): array;

    /**
     * @param int $idMethod
     *
     * @return bool
     */
    public function deleteMethod($idMethod): bool;

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     *
     * @return int|bool
     */
    public function updateMethod(ShipmentMethodTransfer $methodTransfer);

    /**
     * @param int $idShipmentMethod
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findAvailableMethodById($idShipmentMethod, QuoteTransfer $quoteTransfer): ?ShipmentMethodTransfer;

    /**
     * @param int $idShipmentMethod
     *
     * @return bool
     */
    public function isShipmentMethodActive($idShipmentMethod): bool;
}
