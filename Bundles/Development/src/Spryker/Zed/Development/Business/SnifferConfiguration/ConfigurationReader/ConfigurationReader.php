<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\SnifferConfiguration\ConfigurationReader;

use Symfony\Component\Yaml\Parser;

class ConfigurationReader implements ConfigurationReaderInterface
{
    protected const CONFIG_FILE_NAME = 'tooling.yml';

    /**
     * @var \Symfony\Component\Yaml\Parser
     */
    protected $yamlParser;

    /**
     * @param \Symfony\Component\Yaml\Parser $yamlParser
     */
    public function __construct(Parser $yamlParser)
    {
        $this->yamlParser = $yamlParser;
    }

    /**
     * @param string $absoluteModulePath
     *
     * @return array
     */
    public function getModuleConfigurationByAbsolutePath(string $absoluteModulePath): array
    {
        $absoluteModuleSnifferConfigPath = $absoluteModulePath . static::CONFIG_FILE_NAME;

        if (file_exists($absoluteModuleSnifferConfigPath)) {
            return $this->yamlParser->parseFile($absoluteModuleSnifferConfigPath);
        }

        return [];
    }
}
