<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Business\Model;

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
     * @param string $destination
     */
    public function __construct($destination)
    {
        $this->destination = $destination;
    }

    /**
     * @param string $source
     *
     * @return bool
     */
    public function import($source)
    {
        $command = sprintf('sudo cp %s %s', $source, $this->destination);
        $process = new Process(explode(' ', $command), APPLICATION_ROOT_DIR);
        $process->run();

        if ($process->isSuccessful()) {
            return true;
        }

        return false;
    }
}
