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
     * @var \Spryker\Zed\Redis\RedisConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Redis\RedisConfig $config
     */
    public function __construct(RedisConfig $config)
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
        $process = new Process(explode(' ', $command), APPLICATION_ROOT_DIR);
        $process->setTimeout($this->config->getProcessTimeout());
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
}
