<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\ShipmentMethod;

use ArrayObject;

interface ShipmentMethodCollectionRemoverInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethodsTransferList
     * @param int[] $shipmentMethodsTransferForRemoveIndexes
     *
     * @return \ArrayObject
     */
    public function remove(
        ArrayObject $shipmentMethodsTransferList,
        array $shipmentMethodsTransferForRemoveIndexes
    ): ArrayObject;
}
