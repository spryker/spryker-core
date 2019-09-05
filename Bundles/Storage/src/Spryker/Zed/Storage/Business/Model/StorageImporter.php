<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Business\Model;

use Spryker\Zed\Storage\StorageConfig;
use Symfony\Component\Process\Process;

/**
 * @deprecated Use `Spryker\Zed\Redis\Business\Import\RedisImporter` instead.
 */
class StorageImporter implements StorageImporterInterface
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
     * @param string $source
     *
     * @return bool
     */
    public function import($source)
    {
        $command = sprintf('sudo cp %s %s', $source, $this->config->getRdbDumpPath());
        $process = new Process(explode(' ', $command), APPLICATION_ROOT_DIR);
        $process->setTimeout($this->config->getProcessTimeout());
        $process->run();

        if ($process->isSuccessful()) {
            return true;
        }

        return false;
    }
}
