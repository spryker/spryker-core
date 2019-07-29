<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\MySql;

use RuntimeException;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\ExportDatabaseInterface;
use Symfony\Component\Process\Process;

class ExportMySqlDatabase implements ExportDatabaseInterface
{
    /**
     * @param string $backupPath
     *
     * @return void
     */
    public function exportDatabase($backupPath)
    {
        $command = $this->getCommand($backupPath);

        $this->runProcess($command);
    }

    /**
     * @param string $command
     *
     * @throws \RuntimeException
     *
     * @return bool
     */
    protected function runProcess($command)
    {
        $process = new Process(explode(' ', $command), APPLICATION_ROOT_DIR, null, null, 600);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        return (bool)$process->getOutput();
    }

    /**
     * @param string $backupPath
     *
     * @return string
     */
    protected function getCommand($backupPath)
    {
        return sprintf(
            'mysqldump -i -u%s%s %s > %s',
            Config::get(PropelConstants::ZED_DB_USERNAME),
            (empty(Config::get(PropelConstants::ZED_DB_PASSWORD))) ? '' : ' -p' . Config::get(PropelConstants::ZED_DB_PASSWORD),
            Config::get(PropelConstants::ZED_DB_DATABASE),
            $backupPath
        );
    }
}
