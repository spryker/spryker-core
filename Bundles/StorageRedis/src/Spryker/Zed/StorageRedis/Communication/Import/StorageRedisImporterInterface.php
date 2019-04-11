<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StorageRedis\Communication\Import;

interface StorageRedisImporterInterface
{
    /**
     * @param string $source
     *
     * @return bool
     */
    public function import(string $source): bool;
}
