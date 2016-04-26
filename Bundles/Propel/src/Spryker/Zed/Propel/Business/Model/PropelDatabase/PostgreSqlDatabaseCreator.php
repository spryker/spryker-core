<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\PropelConfig;
use Symfony\Component\Process\Process;

class PostgreSqlDatabaseCreator implements DatabaseCreatorInterface
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
    public function createIfNotExists()
    {
        if (!$this->existsDatabase()) {
            $this->createDatabase();
        }
    }

    /**
     * @throws \Exception
     *
     * @return bool
     */
    protected function existsDatabase()
    {
        return $this->runProcess($this->getExistsCommand());
    }

    /**
     * @throws \Exception
     *
     * @return bool
     */
    protected function createDatabase()
    {
        $this->runProcess($this->getCreateCommand());
    }

    /**
     * @return string
     */
    protected function getExistsCommand()
    {
        return sprintf(
            'psql -U %s -lqt | cut -d \| -f 1 | grep -w %s | wc -l',
            'postgres',
            Config::get(PropelConstants::ZED_DB_DATABASE)
        );
    }

    /**
     * @return string
     */
    protected function getCreateCommand()
    {
        return sprintf(
            'psql -U %s -w  -c "CREATE DATABASE \"%s\" WITH ENCODING=\'UTF8\' LC_COLLATE=\'en_US.UTF-8\' LC_CTYPE=\'en_US.UTF-8\' CONNECTION LIMIT=-1 TEMPLATE=\"template0\"; "',
            'postgres',
            Config::get(PropelConstants::ZED_DB_DATABASE)
        );
    }

    /**
     * Check if exit code can be used to return bool
     *
     * @param string $command
     *
     * @return bool
     */
    protected function runProcess($command)
    {
        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        $returnValue = (int)$process->getOutput();

        return (bool)$returnValue;
    }

}
