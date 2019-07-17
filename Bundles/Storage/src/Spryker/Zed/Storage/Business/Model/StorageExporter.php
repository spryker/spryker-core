<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Business\Model;

use Spryker\Zed\Storage\StorageConfig;
use Symfony\Component\Process\Process;

/**
 * @deprecated Use `Spryker\Zed\Redis\Business\Export\RedisExporter` instead.
 */
class StorageExporter implements StorageExporterInterface
{
    /**
     * @var int
     */
    protected $redisPort;

    /**
     * @var int|float|null
     */
    protected $processTimeout;

    /**
     * @param int $redisPort
     * @param int|float|null $processTimeout
     */
    public function __construct($redisPort, ?$processTimeout = StorageConfig::DEFAULT_PROCESS_TIMEOUT)
    {
        $this->redisPort = $redisPort;
        $this->processTimeout = $processTimeout;
    }

    /**
     * @param string $destination
     *
     * @return bool
     */
    public function export($destination)
    {
        $command = sprintf('redis-cli -p %s --rdb %s', $this->redisPort, $destination);
        $process = new Process($command, APPLICATION_ROOT_DIR, null, null, $this->processTimeout);
        $process->run();

        if ($process->isSuccessful()) {
            return true;
        }

        return false;
    }
}
