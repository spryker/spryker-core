<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StoreStorage\Reader;

use Generated\Shared\Transfer\StoreStorageTransfer;

interface StoreStorageReaderInterface
{
    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\StoreStorageTransfer|null
     */
    public function findStoreByName(string $name): ?StoreStorageTransfer;
}
