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
use Spryker\Zed\Propel\Business\Exception\UnsupportedVersionException;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\CreateDatabaseInterface;
use Spryker\Zed\Propel\PropelConfig;
use Symfony\Component\Process\Process;

class CreatePostgreSqlDatabase implements CreateDatabaseInterface
{
    /**
     * @var string
     */
    protected const SHELL_CHARACTERS_PATTERN = '/\$|`/i';

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
            $this->getExistsCommand(),
        );
    }

    /**
     * @return void
     */
    protected function createDatabase()
    {
        $this->runProcess(
            $this->getCreateCommand(),
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
            $this->getConfigValue(PropelConstants::ZED_DB_USERNAME),
            $this->getConfigValue(PropelConstants::ZED_DB_DATABASE),
            $this->getConfigValue(PropelConstants::ZED_DB_DATABASE),
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
            $this->getConfigValue(PropelConstants::ZED_DB_USERNAME),
            $this->getConfigValue(PropelConstants::ZED_DB_DATABASE),
            'postgres',
        );
    }

    /**
     * @return string
     */
    protected function getSudoCreateCommand()
    {
        return sprintf(
            'sudo createdb %s -E UTF8 -T template0',
            $this->getConfigValue(PropelConstants::ZED_DB_DATABASE),
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
        $process = $this->getProcess($command);
        $process->setTimeout($this->config->getProcessTimeout());
        $process->run(null, $this->getEnvironmentVariables());

        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        $returnValue = (int)$process->getOutput();

        return (bool)$returnValue;
    }

    /**
     * @param string $command
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\UnsupportedVersionException
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function getProcess(string $command): Process
    {
        // Shim for Symfony 3.x, to be removed when Symfony dependency becomes 4.2+
        if (!method_exists(Process::class, 'fromShellCommandline')) {
            if (version_compare(PHP_VERSION, '8.0.0', '>=') === true) {
                throw new UnsupportedVersionException('The minimum required version for symfony/process is 4.2.0 to work with PHP 8');
            }

            /**
             * @phpstan-ignore-next-line
             * @psalm-suppress InvalidArgument
             */
            return new Process($command);
        }

        return Process::fromShellCommandline($command);
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
        if (preg_match(static::SHELL_CHARACTERS_PATTERN, $value)) {
            throw new UnSupportedCharactersInConfigurationValueException(sprintf(
                'Configuration value for key "%s" contains unsupported characters (\'$\',\'`\') that is forbidden by security reason.',
                $key,
            ));
        }

        return $value;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getEnvironmentVariables(): array
    {
        return [
            'PGPASSWORD' => $this->getConfigValue(PropelConstants::ZED_DB_PASSWORD),
        ];
    }
}
