<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeStorage\Scanner;

/**
 * @deprecated Exists for BC reasons. Will be removed in the next major release.
 */
interface ShipmentTypeStorageKeyScannerInterface
{
    /**
     * @return list<string>
     */
    public function scanShipmentTypeUuids(): array;
}
