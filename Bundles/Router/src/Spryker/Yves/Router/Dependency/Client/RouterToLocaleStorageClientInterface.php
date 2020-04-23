<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Dependency\Client;

interface RouterToLocaleStorageClientInterface
{
    /**
     * @param string $storeName
     *
     * @return string[]
     */
    public function getLanguagesForStore(string $storeName): array;
}
