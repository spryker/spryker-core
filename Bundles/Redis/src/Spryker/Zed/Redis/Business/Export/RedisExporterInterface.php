<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Redis\Business\Export;

interface RedisExporterInterface
{
    /**
     * @param string $destination
     * @param int|null $redisPort
     *
     * @return bool
     */
    public function export(string $destination, ?int $redisPort = null): bool;
}
