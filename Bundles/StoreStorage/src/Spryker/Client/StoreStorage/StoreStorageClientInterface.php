<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StoreStorage;

interface StoreStorageClientInterface
{
    /**
     * Specification:
     * - Retrieves all available stores.
     *
     * @api
     *
     * @return string[]
     */
    public function getAllStores(): array;
}
