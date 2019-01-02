<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\CodeStyleSniffer\Config;

use Spryker\Zed\Development\Business\SnifferConfiguration\ConfigurationReader\ConfigurationReaderInterface;

class CodeStyleSnifferConfigurationLoader implements CodeStyleSnifferConfigurationLoaderInterface
{
    protected const MODULE_CONFIG_TOOL_KEY = 'code-sniffer';

    /**
     * @var \Spryker\Zed\Development\Business\SnifferConfiguration\ConfigurationReader\ConfigurationReaderInterface
     */
    protected $configurationReader;

    /**
     * @var \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface
     */
    protected $codeStyleSnifferConfiguration;

    /**
     * @param \Spryker\Zed\Development\Business\SnifferConfiguration\ConfigurationReader\ConfigurationReaderInterface $configurationReader
     * @param \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface $codeStyleSnifferConfiguration
     */
    public function __construct(
        ConfigurationReaderInterface $configurationReader,
        CodeStyleSnifferConfigurationInterface $codeStyleSnifferConfiguration
    ) {
        $this->configurationReader = $configurationReader;
        $this->codeStyleSnifferConfiguration = $codeStyleSnifferConfiguration;
    }

    /**
     * @param array $configurationOptions
     * @param string $modulePath
     *
     * @return \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface
     */
    public function load(array $configurationOptions, string $modulePath): CodeStyleSnifferConfigurationInterface
    {
        $this->codeStyleSnifferConfiguration->setConfigurationOptions($configurationOptions);

        $generalModuleConfiguration = $this->configurationReader->getModuleConfigurationByAbsolutePath($modulePath);
        $this->codeStyleSnifferConfiguration->setModuleConfig($generalModuleConfiguration[static::MODULE_CONFIG_TOOL_KEY] ?? []);

        return $this->codeStyleSnifferConfiguration;
    }
}
