<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Redis\Business\Import;

use Spryker\Zed\Redis\RedisConfig;
use Symfony\Component\Process\Process;

class RedisImporter implements RedisImporterInterface
{
    /**
     * @var \Spryker\Zed\Redis\RedisConfig|null
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Redis\RedisConfig|null $config
     */
    public function __construct(?RedisConfig $config = null)
    {
        $this->config = $config;
    }

    /**
     * @param string $source
     * @param string $destination
     *
     * @return bool
     */
    public function import(string $source, string $destination): bool
    {
        $command = $this->buildImportCliCommand($source, $destination);
        $process = new Process($command, APPLICATION_ROOT_DIR, null, null, $this->getProcessTimeout());
        $process->run();

        return $process->isSuccessful();
    }

    /**
     * @param string $source
     * @param string $destination
     *
     * @return string
     */
    protected function buildImportCliCommand(string $source, string $destination): string
    {
        return sprintf('sudo cp %s %s', $source, $destination);
    }

    /**
     * @return int|float|null
     */
    protected function getProcessTimeout()
    {
        if (!$this->config) {
            return RedisConfig::DEFAULT_PROCESS_TIMEOUT;
        }

        return $this->config->getProcessTimeout();
    }
}
