<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Dependency\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @deprecated Use {@link \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodFilterPluginInterface} instead.
 */
interface ShipmentMethodFilterPluginInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @phpstan-param \ArrayObject<int,\Generated\Shared\Transfer\ShipmentMethodTransfer> $shipmentMethods
     *
     * @phpstan-return \ArrayObject<int,\Generated\Shared\Transfer\ShipmentMethodTransfer>|array<int,\Generated\Shared\Transfer\ShipmentMethodTransfer>
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethods
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function filterShipmentMethods(ArrayObject $shipmentMethods, QuoteTransfer $quoteTransfer);
}
