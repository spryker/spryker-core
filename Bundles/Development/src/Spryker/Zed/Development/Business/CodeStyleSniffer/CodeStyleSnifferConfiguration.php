<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\CodeStyleSniffer;

use InvalidArgumentException;
use Spryker\Zed\Development\Business\SnifferConfiguration\ConfigurationReader\ConfigurationReaderInterface;
use Spryker\Zed\Development\DevelopmentConfig;

class CodeStyleSnifferConfiguration
{
    /**
     * @see \Spryker\Zed\Development\Communication\Console\CodeStyleSnifferConsole::OPTION_FIX
     */
    protected const OPTION_FIX = 'fix';

    /**
     * @see \Spryker\Zed\Development\Communication\Console\CodeStyleSnifferConsole::OPTION_DRY_RUN
     */
    protected const OPTION_DRY_RUN = 'dry-run';

    /**
     * @see \Spryker\Zed\Development\Communication\Console\CodeStyleSnifferConsole::OPTION_QUIET
     */
    protected const OPTION_QUIET = 'quiet';

    /**
     * @see \Spryker\Zed\Development\Communication\Console\CodeStyleSnifferConsole::OPTION_EXPLAIN
     */
    protected const OPTION_EXPLAIN = 'explain';

    /**
     * @see \Spryker\Zed\Development\Communication\Console\CodeStyleSnifferConsole::OPTION_SNIFFS
     */
    protected const OPTION_SNIFFS = 'sniffs';

    /**
     * @see \Spryker\Zed\Development\Communication\Console\CodeStyleSnifferConsole::OPTION_VERBOSE
     */
    protected const OPTION_VERBOSE = 'verbose';

    /**
     * @see \Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer::OPTION_IGNORE
     */
    protected const OPTION_IGNORE = 'ignore';

    /**
     * @see \Spryker\Zed\Development\Communication\Console\CodeStyleSnifferConsole::OPTION_LEVEL
     */
    protected const OPTION_LEVEL = 'level';

    protected const MODULE_CONFIG_TOOL_KEY = 'code-sniffer';
    protected const MODULE_CONFIG_LEVEL = 'level';
    protected const LEVELS_ALLOWED = [1, 2];

    /**
     * @var \Spryker\Zed\Development\Business\SnifferConfiguration\ConfigurationReader\ConfigurationReaderInterface
     */
    protected $configurationReader;

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $developmentConfig;

    /**
     * @var array
     */
    protected $moduleConfig;

    /**
     * @var array
     */
    protected $optionConfig;

    /**
     * @param \Spryker\Zed\Development\Business\SnifferConfiguration\ConfigurationReader\ConfigurationReaderInterface $configurationReader
     * @param \Spryker\Zed\Development\DevelopmentConfig $developmentConfig
     */
    public function __construct(ConfigurationReaderInterface $configurationReader, DevelopmentConfig $developmentConfig)
    {
        $this->developmentConfig = $developmentConfig;
        $this->configurationReader = $configurationReader;
    }

    /**
     * @param array $optionConfig
     * @param string $modulePath
     *
     * @return void
     */
    public function load(array $optionConfig, string $modulePath): void
    {
        $this->optionConfig = $optionConfig;
        $this->moduleConfig = $this->configurationReader
                ->getModuleConfigurationByAbsolutePath($modulePath)[static::MODULE_CONFIG_TOOL_KEY] ?? [];
    }

    /**
     * @return string
     */
    public function getCodingStandard(): string
    {
        // TODO: make this dependent on current code sniffer level
        return $this->developmentConfig->getCodingStandard();
    }

    /**
     * @return string|null
     */
    public function getOptionIgnore(): ?string
    {
        return $this->optionConfig[static::OPTION_IGNORE];
    }

    /**
     * @return bool
     */
    public function getOptionFix(): bool
    {
        return $this->optionConfig[static::OPTION_FIX];
    }

    /**
     * @return bool
     */
    public function getOptionQuiet(): bool
    {
        return $this->optionConfig[static::OPTION_QUIET];
    }

    /**
     * @return bool
     */
    public function getOptionDryRun(): bool
    {
        return $this->optionConfig[static::OPTION_DRY_RUN];
    }

    /**
     * @return bool
     */
    public function getOptionExplain(): bool
    {
        return $this->optionConfig[static::OPTION_EXPLAIN];
    }

    /**
     * @return string|null
     */
    public function getOptionSniffs(): ?string
    {
        return $this->optionConfig[static::OPTION_SNIFFS];
    }

    /**
     * @return bool
     */
    public function getOptionVerbose(): bool
    {
        return $this->optionConfig[static::OPTION_VERBOSE];
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return int
     */
    public function getOptionLevel(): int
    {
        $optionLevel = $this->resolveOptionLevel();

        if (!in_array($optionLevel, static::LEVELS_ALLOWED)) {
            throw new InvalidArgumentException(
                sprintf('Level should be in [%s] range', implode(', ', static::LEVELS_ALLOWED))
            );
        }

        return $optionLevel;
    }

    /**
     * @return int
     */
    protected function resolveOptionLevel(): int
    {
        $optionLevel = $this->optionConfig[static::OPTION_LEVEL];

        if ($optionLevel !== null) {
            return (int)$optionLevel;
        }

        if (isset($this->moduleConfig[static::MODULE_CONFIG_LEVEL])) {
            return $this->moduleConfig[static::MODULE_CONFIG_LEVEL];
        }

        return $this->developmentConfig->getCodeSnifferLevel();
    }
}
