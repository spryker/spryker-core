<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\SnifferConfiguration\Builder;

use Spryker\Zed\Development\Business\SnifferConfiguration\ConfigurationReader\ConfigurationReaderInterface;
use Spryker\Zed\Development\DevelopmentConfig;

class ArchitectureSnifferConfigurationBuilder implements SnifferConfigurationBuilderInterface
{
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

        if ($priority === DevelopmentConfig::ARCHITECTURE_SNIFFER_OPTION_VALUE_PRIORITY_SKIP) {
            return [];
        }

        $options[DevelopmentConfig::ARCHITECTURE_SNIFFER_OPTION_NAME_PRIORITY] = $priority;

        return $options;
    }

    /**
     * @param array $moduleConfig
     * @param array $options
     *
     * @return int
     */
    protected function getPriority(array $moduleConfig, array $options = []): int
    {
        $inputPriority = $options[DevelopmentConfig::ARCHITECTURE_SNIFFER_OPTION_NAME_PRIORITY];

        if ($inputPriority !== null && is_numeric($inputPriority)) {
            return $inputPriority;
        }

        return $this->getConfigPriority($moduleConfig);
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

        $architectureSnifferPriority = $this->getArchitectureSnifferConfigPriority($architectureSnifferConfig);

        if ($architectureSnifferPriority < 0) {
            return $this->defaultPriorityLevel;
        }

        return $architectureSnifferPriority;
    }

    /**
     * @param array $moduleConfig
     *
     * @return bool
     */
    protected function architectureSnifferConfigExists(array $moduleConfig): bool
    {
        return isset($moduleConfig[DevelopmentConfig::ARCHITECTURE_SNIFFER_CONFIG_NAME]);
    }

    /**
     * @param array $moduleConfig
     *
     * @return array
     */
    protected function getArchitectureSnifferConfig(array $moduleConfig): array
    {
        return $moduleConfig[DevelopmentConfig::ARCHITECTURE_SNIFFER_CONFIG_NAME];
    }

    /**
     * @param array $architectureSnifferConfig
     *
     * @return bool
     */
    protected function architectureSnifferConfigPriorityExists(array $architectureSnifferConfig): bool
    {
        return isset($architectureSnifferConfig[DevelopmentConfig::ARCHITECTURE_SNIFFER_OPTION_NAME_PRIORITY]);
    }

    /**
     * @param array $architectureSnifferConfig
     *
     * @return int
     */
    protected function getArchitectureSnifferConfigPriority(array $architectureSnifferConfig): int
    {
        return (int)$architectureSnifferConfig[DevelopmentConfig::ARCHITECTURE_SNIFFER_OPTION_NAME_PRIORITY];
    }
}
