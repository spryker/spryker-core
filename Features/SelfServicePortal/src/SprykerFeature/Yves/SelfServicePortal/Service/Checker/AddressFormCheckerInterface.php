<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Checker;

use Generated\Shared\Transfer\ItemTransfer;

interface AddressFormCheckerInterface
{
    public function isApplicableForSingleAddressPerShipmentType(
        ItemTransfer $itemTransfer
    ): bool;

    public function isApplicableShipmentType(string $shipmentTypeKey): bool;
}
