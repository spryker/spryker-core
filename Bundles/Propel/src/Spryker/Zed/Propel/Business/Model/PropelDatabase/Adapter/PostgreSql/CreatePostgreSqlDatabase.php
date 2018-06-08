<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql;

use RuntimeException;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\Business\Exception\UnSupportedCharactersInConfigurationValueException;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\CreateDatabaseInterface;
use Symfony\Component\Process\Process;

class CreatePostgreSqlDatabase implements CreateDatabaseInterface
{
    protected const SHELL_CHARACTER = '$';
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
     * @return bool
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
            'PGPASSWORD=%s psql -h %s -p %s -U %s -w -lqt %s | cut -d \| -f 1 | grep -w %s | wc -l',
            $this->getConfigValue(PropelConstants::ZED_DB_PASSWORD),
            Config::get(PropelConstants::ZED_DB_HOST),
            Config::get(PropelConstants::ZED_DB_PORT),
            $this->getConfigValue(PropelConstants::ZED_DB_USERNAME),
            $this->getConfigValue(PropelConstants::ZED_DB_DATABASE),
            $this->getConfigValue(PropelConstants::ZED_DB_DATABASE)
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
            'PGPASSWORD=%s psql -h %s -p %s -U %s -w -c "CREATE DATABASE \"%s\" WITH ENCODING=\'UTF8\' LC_COLLATE=\'en_US.UTF-8\' LC_CTYPE=\'en_US.UTF-8\' CONNECTION LIMIT=-1 TEMPLATE=\"template0\"; " %s',
            $this->getConfigValue(PropelConstants::ZED_DB_PASSWORD),
            Config::get(PropelConstants::ZED_DB_HOST),
            Config::get(PropelConstants::ZED_DB_PORT),
            $this->getConfigValue(PropelConstants::ZED_DB_USERNAME),
            $this->getConfigValue(PropelConstants::ZED_DB_DATABASE),
            'postgres'
        );
    }

    /**
     * @return string
     */
    protected function getSudoCreateCommand()
    {
        return sprintf(
            'PGPASSWORD=%s sudo createdb %s -E UTF8 -T template0',
            $this->getConfigValue(PropelConstants::ZED_DB_PASSWORD),
            $this->getConfigValue(PropelConstants::ZED_DB_DATABASE)
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
        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        $returnValue = (int)$process->getOutput();

        return (bool)$returnValue;
    }

    /**
     * @return bool
     */
    protected function useSudo()
    {
        return Config::get(PropelConstants::USE_SUDO_TO_MANAGE_DATABASE, true);
    }

    /**
     * @param string $key
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\UnSupportedCharactersInConfigurationValueException
     *
     * @return mixed
     */
    protected function getConfigValue(string $key)
    {
        $value = Config::get($key);
        if (mb_strpos($value, static::SHELL_CHARACTER) !== false) {
            throw new UnSupportedCharactersInConfigurationValueException(sprintf(
                'Configuration value for key "%s" contains unsupported characters (\'%s\') that is forbidden by security reason.',
                $key,
                static::SHELL_CHARACTER
            ));
        }

        return $value;
    }
}
