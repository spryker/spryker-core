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
     * @var \Spryker\Zed\Storage\StorageConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Storage\StorageConfig $config
     */
    public function __construct(StorageConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $destination
     *
     * @return bool
     */
    public function export($destination)
    {
        $command = sprintf('redis-cli -p %s --rdb %s', $this->config->getRedisPort(), $destination);
        $process = new Process(explode(' ', $command), APPLICATION_ROOT_DIR);
        $process->setTimeout($this->config->getProcessTimeout());
        $process->run();

        if ($process->isSuccessful()) {
            return true;
        }

        return false;
    }
}
