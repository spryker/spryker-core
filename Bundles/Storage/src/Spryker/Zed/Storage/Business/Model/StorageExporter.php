<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Business\Model;

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
     * @param int $redisPort
     */
    public function __construct($redisPort)
    {
        $this->redisPort = $redisPort;
    }

    /**
     * @param string $destination
     *
     * @return bool
     */
    public function export($destination)
    {
        $command = sprintf('redis-cli -p %s --rdb %s', $this->redisPort, $destination);
        $process = new Process(explode(' ', $command), APPLICATION_ROOT_DIR);
        $process->run();

        if ($process->isSuccessful()) {
            return true;
        }

        return false;
    }
}
