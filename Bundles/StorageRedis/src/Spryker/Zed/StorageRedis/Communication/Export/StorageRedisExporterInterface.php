<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StorageRedis\Communication\Export;

interface StorageRedisExporterInterface
{
    /**
     * @param string $destination
     *
     * @return bool
     */
    public function export(string $destination): bool;
}
