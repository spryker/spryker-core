<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Drop;

use RuntimeException;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\EngineAwareCommandInterface;
use Spryker\Zed\Propel\PropelConfig;
use Symfony\Component\Process\Process;

class PostgreSqlDatabaseDropper implements EngineAwareCommandInterface
{

    /**
     * @return string
     */
    public function getEngine()
    {
        return PropelConfig::DB_ENGINE_PGSQL;
    }

    /**
     * @return void
     */
    public function __invoke()
    {
        $this->dropDatabase();
    }

    /**
     * @return bool
     */
    protected function dropDatabase()
    {
        $this->runProcess(
            $this->getDropCommand()
        );
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
     * Check if exit code can be used to return bool
     *
     * @param string $command
     *
     * @throws \RuntimeException
     *
     * @return bool
     */
    protected function runProcess($command)
    {
        $this->exportPostgresPassword();

        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        $returnValue = (int)$process->getOutput();

        return (bool)$returnValue;
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
