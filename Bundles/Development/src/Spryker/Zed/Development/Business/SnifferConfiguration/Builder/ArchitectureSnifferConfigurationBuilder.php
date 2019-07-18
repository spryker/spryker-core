<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\SnifferConfiguration\Builder;

use Spryker\Zed\Development\Business\Exception\ArchitectureSniffer\InvalidTypeException;
use Spryker\Zed\Development\Business\SnifferConfiguration\ConfigurationReader\ConfigurationReaderInterface;

class ArchitectureSnifferConfigurationBuilder implements SnifferConfigurationBuilderInterface
{
    protected const CONFIG_NAME = 'architecture-sniffer';
    protected const CONFIG_PRIORITY_NAME = 'priority';
    protected const CONFIG_PRIORITY_SKIP_VALUE = 0;
    protected const CONFIG_IGNORE_ERRORS = 'ignoreErrors';

    /**
     * @var \Spryker\Zed\Development\Business\SnifferConfiguration\ConfigurationReader\ConfigurationReaderInterface
     */
    protected $configurationReader;

    /**
     * @var int
     */
    protected $defaultPriorityLevel;

    /**
     * @param \Spryker\Zed\Development\Business\SnifferConfiguration\ConfigurationReader\ConfigurationReaderInterface $configurationReader
     * @param int $defaultPriorityLevel
     */
    public function __construct(ConfigurationReaderInterface $configurationReader, int $defaultPriorityLevel)
    {
        $this->configurationReader = $configurationReader;
        $this->defaultPriorityLevel = $defaultPriorityLevel;
    }

    /**
     * @param string $absoluteModulePath
     * @param array $options
     *
     * @return array
     */
    public function getConfiguration(string $absoluteModulePath, array $options = []): array
    {
        $moduleConfig = $this->configurationReader->getModuleConfigurationByAbsolutePath($absoluteModulePath);

        $priority = $this->getPriority(
            $moduleConfig,
            $options
        );

        if ($priority === static::CONFIG_PRIORITY_SKIP_VALUE) {
            return [];
        }

        $options[static::CONFIG_PRIORITY_NAME] = $priority;
        $options[static::CONFIG_IGNORE_ERRORS] = $this->getIgnoreErrors($moduleConfig);

        return $options;
    }

    /**
     * @param array $moduleConfig
     * @param array $options
     *
     * @throws \Spryker\Zed\Development\Business\Exception\ArchitectureSniffer\InvalidTypeException
     *
     * @return int
     */
    protected function getPriority(array $moduleConfig, array $options = []): int
    {
        if (!isset($options[static::CONFIG_PRIORITY_NAME])) {
            return $this->getConfigPriority($moduleConfig);
        }

        $userPriorityOption = $options[static::CONFIG_PRIORITY_NAME];

        $isInteger = is_string($userPriorityOption) ? ctype_digit($userPriorityOption) : is_int($userPriorityOption);

        if (!$isInteger) {
            throw new InvalidTypeException('Priority must be integer only.');
        }

        return $userPriorityOption;
    }

    /**
     * @param array $moduleConfig
     *
     * @return int
     */
    protected function getConfigPriority(array $moduleConfig): int
    {
        if (!$this->architectureSnifferConfigExists($moduleConfig)) {
            return $this->defaultPriorityLevel;
        }

        $architectureSnifferConfig = $this->getArchitectureSnifferConfig($moduleConfig);

        if (!$this->architectureSnifferConfigPriorityExists($architectureSnifferConfig)) {
            return $this->defaultPriorityLevel;
        }

        return $this->getArchitectureSnifferConfigPriority($architectureSnifferConfig);
    }

    /**
     * @param array $moduleConfig
     *
     * @return bool
     */
    protected function architectureSnifferConfigExists(array $moduleConfig): bool
    {
        return isset($moduleConfig[static::CONFIG_NAME]);
    }

    /**
     * @param array $moduleConfig
     *
     * @return array
     */
    protected function getArchitectureSnifferConfig(array $moduleConfig): array
    {
        return $moduleConfig[static::CONFIG_NAME];
    }

    /**
     * @param array $architectureSnifferConfig
     *
     * @return bool
     */
    protected function architectureSnifferConfigPriorityExists(array $architectureSnifferConfig): bool
    {
        return isset($architectureSnifferConfig[static::CONFIG_PRIORITY_NAME]);
    }

    /**
     * @param array $architectureSnifferConfig
     *
     * @return int
     */
    protected function getArchitectureSnifferConfigPriority(array $architectureSnifferConfig): int
    {
        return (int)$architectureSnifferConfig[static::CONFIG_PRIORITY_NAME];
    }

    /**
     * @param array $moduleConfig
     *
     * @return string[]
     */
    protected function getIgnoreErrors(array $moduleConfig): array
    {
        if (!$this->architectureSnifferConfigExists($moduleConfig)) {
            return [];
        }

        $architectureSnifferConfig = $this->getArchitectureSnifferConfig($moduleConfig);

        return $architectureSnifferConfig[static::CONFIG_IGNORE_ERRORS] ?? [];
    }
}
