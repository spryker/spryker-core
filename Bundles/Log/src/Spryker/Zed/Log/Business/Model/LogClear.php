<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log\Business\Model;

use Symfony\Component\Filesystem\Filesystem;

class LogClear implements LogClearInterface
{
    /**
     * @var array
     */
    protected $logFileDirectories = [];

    /**
     * @param array $logFileDirectories
     */
    public function __construct(array $logFileDirectories)
    {
        $this->logFileDirectories = $logFileDirectories;
    }

    /**
     * @return void
     */
    public function clearLogs()
    {
        $filesystem = new Filesystem();
        foreach ($this->logFileDirectories as $logFileDirectory) {
            if (is_dir($logFileDirectory)) {
                $filesystem->remove($logFileDirectory);
            }
        }
    }
}
