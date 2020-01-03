<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase;

use RuntimeException;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\PropelConfig;
use Symfony\Component\Process\Process;

class PostgreSqlDatabaseCreator implements DatabaseCreatorInterface
{
    /**
     * @var \Spryker\Zed\Propel\PropelConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Propel\PropelConfig $config
     */
    public function __construct(PropelConfig $config)
    {
        $this->config = $config;
    }

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
    public function createIfNotExists()
    {
        if (!$this->existsDatabase()) {
            $this->createDatabase();
        }
    }

    /**
     * @return bool
     */
    protected function existsDatabase()
    {
        return $this->runProcess(
            $this->getExistsCommand()
        );
    }

    /**
     * @return void
     */
    protected function createDatabase()
    {
        $this->runProcess(
            $this->getCreateCommand()
        );
    }

    /**
     * @return string
     */
    protected function getExistsCommand()
    {
        return sprintf(
            'psql -h %s -p %s -U %s -w -lqt %s | cut -d \| -f 1 | grep -w %s | wc -l',
            Config::get(PropelConstants::ZED_DB_HOST),
            Config::get(PropelConstants::ZED_DB_PORT),
            Config::get(PropelConstants::ZED_DB_USERNAME),
            Config::get(PropelConstants::ZED_DB_DATABASE),
            Config::get(PropelConstants::ZED_DB_DATABASE)
        );
    }

    /**
     * @return string
     */
    protected function getCreateCommand()
    {
        if ($this->useSudo()) {
            return $this->getSudoCreateCommand();
        }

        return $this->getCreateCommandRemote();
    }

    /**
     * @return string
     */
    protected function getCreateCommandRemote()
    {
        return sprintf(
            'psql -h %s -p %s -U %s -w -c "CREATE DATABASE \"%s\" WITH ENCODING=\'UTF8\' LC_COLLATE=\'en_US.UTF-8\' LC_CTYPE=\'en_US.UTF-8\' CONNECTION LIMIT=-1 TEMPLATE=\"template0\"; " %s',
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
    protected function getSudoCreateCommand()
    {
        return sprintf(
            'sudo createdb %s -E UTF8 -T template0',
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

        $process = new Process(explode(' ', $command));
        $process->setTimeout($this->config->getProcessTimeout());
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
}
