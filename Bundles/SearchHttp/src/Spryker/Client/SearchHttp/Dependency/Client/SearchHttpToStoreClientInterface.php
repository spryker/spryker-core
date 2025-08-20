<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Dependency\Client;

use Generated\Shared\Transfer\StoreTransfer;

interface SearchHttpToStoreClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore(): StoreTransfer;

    /**
     * @return bool
     */
    public function isCurrentStoreDefined(): bool;
}
