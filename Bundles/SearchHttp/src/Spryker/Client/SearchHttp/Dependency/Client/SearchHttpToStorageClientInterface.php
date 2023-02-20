<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Dependency\Client;

interface SearchHttpToStorageClientInterface
{
    /**
     * @param string $key
     *
     * @return array<string, mixed>|null
     */
    public function get(string $key): ?array;
}
