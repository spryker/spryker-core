<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Locale\Dependency\Client;

use Generated\Shared\Transfer\StoreTransfer;

interface LocaleToStoreClientInterface
{
    /**
     * @return bool
     */
    public function isDynamicStoreEnabled(): bool;

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore(): StoreTransfer;
}
