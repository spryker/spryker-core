<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerStorage\Dependency\Client;

interface CustomerStorageToStorageClientInterface
{
    /**
     * @param array<string> $keys
     *
     * @return array<string, string>
     */
    public function getMulti(array $keys): array;
}
