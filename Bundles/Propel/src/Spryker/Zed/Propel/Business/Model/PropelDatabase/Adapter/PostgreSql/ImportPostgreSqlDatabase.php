<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql;

use RuntimeException;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\ImportDatabaseInterface;
use Symfony\Component\Process\Process;

class ImportPostgreSqlDatabase implements ImportDatabaseInterface
{
    /**
     * @param string $backupPath
     *
     * @return void
     */
    public function importDatabase($backupPath)
    {
        $this->runProcess(
            $this->getImportCommand($backupPath)
        );
    }

    /**
     * @param string $backupPath
     *
     * @return string
     */
    protected function getImportCommand($backupPath)
    {
        if ($this->useSudo()) {
            return $this->getSudoImportCommand($backupPath);
        }

        return $this->getImportCommandRemote($backupPath);
    }

    /**
     * @param string $backupPath
     *
     * @return string
     */
    protected function getImportCommandRemote($backupPath)
    {
        return sprintf(
            'pg_restore -h %s -p %s -U %s -d %s -v %s',
            Config::get(PropelConstants::ZED_DB_HOST),
            Config::get(PropelConstants::ZED_DB_PORT),
            Config::get(PropelConstants::ZED_DB_USERNAME),
            Config::get(PropelConstants::ZED_DB_DATABASE),
            $backupPath
        );
    }

    /**
     * @param string $backupPath
     *
     * @return string
     */
    protected function getSudoImportCommand($backupPath)
    {
        return sprintf(
            'sudo pg_restore -d %s %s',
            Config::get(PropelConstants::ZED_DB_DATABASE),
            $backupPath
        );
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
        $this->exportPostgresPassword();

        $process = $this->getProcess($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        $returnValue = (int)$process->getOutput();

        return (bool)$returnValue;
    }

    /**
     * @param string $command
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function getProcess(string $command): Process
    {
        if (method_exists(Process::class, 'fromShellCommandline')) {
            return Process::fromShellCommandline($command);
        }

        return new Process($command);
    }

    /**
     * @return void
     */
    protected function exportPostgresPassword()
    {
        putenv(sprintf(
            'PGPASSWORD=%s',
            Config::get(PropelConstants::ZED_DB_PASSWORD)
        ));
    }

    /**
     * @return bool
     */
    protected function useSudo()
    {
        return Config::get(PropelConstants::USE_SUDO_TO_MANAGE_DATABASE, true);
    }
}
