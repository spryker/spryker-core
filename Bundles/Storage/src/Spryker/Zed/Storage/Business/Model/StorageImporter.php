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
     * @var string
     */
    protected $destination;

    /**
     * @var int|float|null
     */
    protected $processTimeout;

    /**
     * @param string $destination
     * @param int|float|null $processTimeout
     */
    public function __construct($destination, ?$processTimeout = StorageConfig::DEFAULT_PROCESS_TIMEOUT)
    {
        $this->destination = $destination;
        $this->processTimeout = $processTimeout;
    }

    /**
     * @param string $source
     *
     * @return bool
     */
    public function import($source)
    {
        $command = sprintf('sudo cp %s %s', $source, $this->destination);
        $process = new Process($command, APPLICATION_ROOT_DIR, null, null, $this->processTimeout);
        $process->run();

        if ($process->isSuccessful()) {
            return true;
        }

        return false;
    }
}
