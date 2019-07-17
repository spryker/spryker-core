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
use Spryker\Zed\Propel\PropelConfig;
use Symfony\Component\Process\Process;

class ExportMySqlDatabase implements ExportDatabaseInterface
{
    protected const MINIMUM_PROCESS_TIMEOUT = 600;

    /**
     * @var \Spryker\Zed\Propel\PropelConfig|null
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Propel\PropelConfig|null $config
     */
    public function __construct(?PropelConfig $config = null)
    {
        $this->config = $config;
    }

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
        $process = new Process($command, APPLICATION_ROOT_DIR, null, null, $this->getProcessTimeout());
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

    /**
     * @return int|float|null
     */
    protected function getProcessTimeout()
    {
        $minimumProcessTimeout = static::MINIMUM_PROCESS_TIMEOUT;
        $processTimeout = PropelConfig::DEFAULT_PROCESS_TIMEOUT;

        if ($this->config) {
            $minimumProcessTimeout = $this->config->getMinimumMySqlDatabaseExportTimeout();
            $processTimeout = $this->config->getProcessTimeout();
        }

        return $processTimeout > $minimumProcessTimeout ? $processTimeout : $minimumProcessTimeout;
    }
}
