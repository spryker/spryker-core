<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Validator\Rule;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;

interface ShipmentTypeValidatorRuleInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $shipmentTypeTransfers, ErrorCollectionTransfer $errorCollectionTransfer): ErrorCollectionTransfer;
}
