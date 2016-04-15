<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model;

use Spryker\Shared\Library\Json;
use Spryker\Zed\Propel\Business\Exception\ConfigFileNotCreatedException;
use Spryker\Zed\Propel\Business\Exception\ConfigMissingPropertyException;

class PropelConfigConverterJson
{

    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->validateConfig();

        $this->fixMissingZedConfig();

        $this->createTargetDirectoryIfNotExists();
    }

    /**
     * This method can be removed when clients get fixed `config/Shared/propel.php` config
     *
     * @return void
     */
    private function fixMissingZedConfig()
    {
        if (empty($this->config['database']['connections']['zed'])) {
            $this->config['database']['connections']['zed'] = $this->config['database']['connections']['default'];
        }
    }

    /**
     * @throws \Spryker\Zed\Propel\Business\Exception\ConfigMissingPropertyException
     *
     * @return void
     */
    protected function validateConfig()
    {
        if (empty($this->config['paths']['phpConfDir'])) {
            throw new ConfigMissingPropertyException('Could not find "phpConfDir" configuration');
        }
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function convertConfig()
    {
        $this->writeToFile();
        $this->validateFileExists();
    }

    /**
     * @return void
     */
    protected function writeToFile()
    {
        file_put_contents($this->getFileName(), $this->convertToJson());
    }

    /**
     * @return string
     */
    protected function convertToJson()
    {
        $config = ['propel' => $this->config];

        return Json::encode($config);
    }

    /**
     * @return string
     */
    protected function getTargetDirectory()
    {
        return $this->config['paths']['phpConfDir'];
    }

    /**
     * @return void
     */
    protected function createTargetDirectoryIfNotExists()
    {
        $configDirectory = $this->getTargetDirectory();

        if (!is_dir($configDirectory)) {
            $this->createTargetDirectory($configDirectory);
        }
    }

    /**
     * @param string $configDirectory
     *
     * @return void
     */
    protected function createTargetDirectory($configDirectory)
    {
        mkdir($configDirectory, 0775, true);
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        return $this->getTargetDirectory() . DIRECTORY_SEPARATOR . 'propel.json';
    }

    /**
     * @throws \Spryker\Zed\Propel\Business\Exception\ConfigFileNotCreatedException
     *
     * @return void
     */
    protected function validateFileExists()
    {
        if (!is_file($this->getFileName())) {
            throw new ConfigFileNotCreatedException(sprintf('Could not create config file "%s"', $this->getFileName()));
        }
    }

}
