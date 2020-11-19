<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Redis\Logger;

interface RedisLoggerInterface
{
    /**
     * @param string $command
     * @param array $payload
     * @param mixed|null $result
     *
     * @return void
     */
    public function log(string $command, array $payload, $result = null);

    /**
     * @return string[][]
     */
    public function getLogs(): array;
}
