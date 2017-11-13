<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Engine\PostgreSql;

use RuntimeException;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseInterface;
use Symfony\Component\Process\Process;

class DropPostgreSqlDatabase implements DropDatabaseInterface
{
    /**
     * @return bool
     */
    public function dropDatabase()
    {
        if ($this->useSudo()) {
            return $this->runSudoDropCommand();
        }

        return $this->runDropCommandRemote();
    }

    /**
     * @return bool
     */
    protected function runSudoDropCommand()
    {
        $this->closeOpenConnections();

        return $this->runProcess($this->getSudoDropCommand());
    }

    /**
     * @return bool
     */
    protected function runDropCommandRemote()
    {
        return $this->runProcess($this->getDropCommand());
    }

    /**
     * @throws \RuntimeException
     *
     * @return void
     */
    protected function closeOpenConnections()
    {
        $postgresVersion = $this->getPostgresVersion();
        $process = $this->getProcess(sprintf('sudo pg_ctlcluster %s main restart --force', $postgresVersion));
        $process->run();
        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }
    }

    /**
     * @throws \RuntimeException
     *
     * @return string
     */
    protected function getPostgresVersion()
    {
        $process = $this->getProcess('psql --version | awk \'{print $3}\' | cut -f1,2 -d\'.\'');
        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        return trim($process->getOutput(), "\n");
    }

    /**
     * @return string
     */
    protected function getDropCommand()
    {
        if ($this->useSudo()) {
            return $this->getSudoDropCommand();
        }

        return $this->getDropCommandRemote();
    }

    /**
     * @return string
     */
    protected function getDropCommandRemote()
    {
        return sprintf(
            'psql -h %s -p %s -U %s -w -c "DROP DATABASE IF EXISTS \"%s\"; " %s',
            Config::get(PropelConstants::ZED_DB_HOST),
            Config::get(PropelConstants::ZED_DB_PORT),
            Config::get(PropelConstants::ZED_DB_USERNAME),
            Config::get(PropelConstants::ZED_DB_DATABASE),
            'postgres'
        );
    }

    /**
     * @return string
     */
    protected function getSudoDropCommand()
    {
        return sprintf(
            'sudo dropdb %s --if-exists',
            Config::get(PropelConstants::ZED_DB_DATABASE)
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

        return true;
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

    /**
     * @param string $command
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function getProcess($command)
    {
        $process = new Process($command);

        return $process;
    }
}
