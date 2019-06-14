<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\Storage\Reader;

interface StorageReaderFactoryInterface
{
    /**
     * @return \Spryker\Client\StorageDatabase\Storage\Reader\AbstractStorageReader
     */
    public function createStorageReader(): AbstractStorageReader;
}
