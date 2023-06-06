<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeStorage\Dependency\Client;

interface ShipmentTypeStorageToStorageClientInterface
{
    /**
     * @param list<string> $keys
     *
     * @return array<string, string|null>
     */
    public function getMulti(array $keys): array;
}
