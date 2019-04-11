<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Redis\Business\Export;

use Spryker\Zed\Redis\Business\Exception\RedisExportException;
use Symfony\Component\Process\Process;

class RedisExporter implements RedisExporterInterface
{
    /**
     * @param string $destination
     * @param int|null $redisPort
     *
     * @throws \Spryker\Zed\Redis\Business\Exception\RedisExportException
     *
     * @return bool
     */
    public function export(string $destination, ?int $redisPort = null): bool
    {
        if ($redisPort === null) {
            throw new RedisExportException('No port specified for Redis export.');
        }

        $command = sprintf('redis-cli -p %s --rdb %s', $redisPort, $destination);
        $process = new Process($command, APPLICATION_ROOT_DIR);
        $process->run();

        if ($process->isSuccessful()) {
            return true;
        }

        return false;
    }
}
