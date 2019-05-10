<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Redis\Business\Import;

use Symfony\Component\Process\Process;

class RedisImporter implements RedisImporterInterface
{
    /**
     * @param string $source
     * @param string $destination
     *
     * @return bool
     */
    public function import(string $source, string $destination): bool
    {
        $command = sprintf('sudo cp %s %s', $source, $destination);
        $process = new Process($command, APPLICATION_ROOT_DIR);
        $process->run();

        return $process->isSuccessful();
    }
}
