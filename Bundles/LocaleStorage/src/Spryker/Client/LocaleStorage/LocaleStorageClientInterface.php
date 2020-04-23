<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\LocaleStorage;

interface LocaleStorageClientInterface
{
    /**
     * Specification:
     * - Retrieves available languages for the given store.
     *
     * @api
     *
     * @param string $storeName
     *
     * @return array
     */
    public function getLanguagesForStore(string $storeName): array;
}
