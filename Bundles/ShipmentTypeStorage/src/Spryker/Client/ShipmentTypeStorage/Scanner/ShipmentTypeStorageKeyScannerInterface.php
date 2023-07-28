<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeStorage\Scanner;

interface ShipmentTypeStorageKeyScannerInterface
{
    /**
     * @return list<string>
     */
    public function scanShipmentTypeUuids(): array;
}
