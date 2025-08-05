<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\CodeStyleSniffer\Config;

use InvalidArgumentException;
use Spryker\Zed\Development\DevelopmentConfig;

class CodeStyleSnifferConfiguration implements CodeStyleSnifferConfigurationInterface
{
    /**
     * @see \Spryker\Zed\Development\Communication\Console\CodeStyleSnifferConsole::OPTION_FIX
     *
     * @var string
     */
    protected const OPTION_FIX = 'fix';

    /**
     * @see \Spryker\Zed\Development\Communication\Console\CodeStyleSnifferConsole::OPTION_DRY_RUN
     *
     * @var string
     */
    protected const OPTION_DRY_RUN = 'dry-run';

    /**
     * @see \Spryker\Zed\Development\Communication\Console\CodeStyleSnifferConsole::OPTION_QUIET
     *
     * @var string
     */
    protected const OPTION_QUIET = 'quiet';

    /**
     * @see \Spryker\Zed\Development\Communication\Console\CodeStyleSnifferConsole::OPTION_EXPLAIN
     *
     * @var string
     */
    protected const OPTION_EXPLAIN = 'explain';

    /**
     * @see \Spryker\Zed\Development\Communication\Console\CodeStyleSnifferConsole::OPTION_SNIFFS
     *
     * @var string
     */
    protected const OPTION_SNIFFS = 'sniffs';

    /**
     * @see \Spryker\Zed\Development\Communication\Console\CodeStyleSnifferConsole::OPTION_VERBOSE
     *
     * @var string
     */
    protected const OPTION_VERBOSE = 'verbose';

    /**
     * @see \Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer::OPTION_IGNORE
     *
     * @var string
     */
    protected const OPTION_IGNORE = 'ignore';

    /**
     * @see \Spryker\Zed\Development\Business\CodeStyleSniffer\CodeStyleSniffer::OPTION_MODULE
     *
     * @var string
     */
    protected const OPTION_MODULE = 'module';

    /**
     * @see \Spryker\Zed\Development\Communication\Console\CodeStyleSnifferConsole::OPTION_LEVEL
     *
     * @var string
     */
    protected const OPTION_LEVEL = 'level';

    /**
     * @var string
     */
    protected const MODULE_CONFIG_LEVEL = 'level';

    /**
     * @var int
     */
    protected const LEVEL_SPRYKER_BASIC = 1;

    /**
     * @var int
     */
    protected const LEVEL_SPRYKER_STRICT = 2;

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $developmentConfig;

    /**
     * @var array<string, mixed>
     */
    protected $moduleConfig;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var array<string, mixed>
     */
    protected $configurationOptions;

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $developmentConfig
     */
    public function __construct(DevelopmentConfig $developmentConfig)
    {
        $this->developmentConfig = $developmentConfig;
    }

    /**
     * @param array<string, mixed> $moduleConfig
     *
     * @return $this
     */
    public function setModuleConfig(array $moduleConfig)
    {
        $this->moduleConfig = $moduleConfig;

        return $this;
    }

    /**
     * @param string $namespace
     *
     * @return $this
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param array<string, mixed> $configurationOptions
     *
     * @return $this
     */
    public function setConfigurationOptions(array $configurationOptions)
    {
        $this->configurationOptions = $configurationOptions;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $path
     *
     * @return string
     */
    public function getCodingStandard(string $path): string
    {
        $phpcsRootFilePath = $path . 'phpcs.xml';

        if (file_exists($phpcsRootFilePath)) {
            return $phpcsRootFilePath;
        }

        $vendorDir = APPLICATION_VENDOR_DIR . DIRECTORY_SEPARATOR;

        $localConfigPath = '';
        if (strpos($path, $vendorDir) === false && !$this->developmentConfig->isStandaloneMode()) {
            return $this->developmentConfig->getCodingStandard();
        }

        if ($this->getLevel() === static::LEVEL_SPRYKER_STRICT) {
            $localConfigPath = $this->developmentConfig->getCodeSnifferStrictRuleset();
        }

        if ($this->getLevel() === static::LEVEL_SPRYKER_STRICT && $this->getNamespace() === $this->developmentConfig->getSprykerFeatureNamespace()) {
            $localConfigPath = $this->developmentConfig->getCodeSnifferFeatureRuleset();
        }

        if (file_exists($localConfigPath)) {
            return $localConfigPath;
        }

        $localConfigPath = $this->developmentConfig->getCodeSnifferRuleset();

        if (file_exists($localConfigPath)) {
            return $localConfigPath;
        }

        return $this->developmentConfig->getCodingStandard();
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getIgnoredPaths(): ?string
    {
        return $this->configurationOptions[static::OPTION_IGNORE];
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getModule(): ?string
    {
        return $this->configurationOptions[static::OPTION_MODULE];
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function isFixing(): bool
    {
        return $this->configurationOptions[static::OPTION_FIX];
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function isQuiet(): bool
    {
        return $this->configurationOptions[static::OPTION_QUIET];
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function isDryRun(): bool
    {
        return $this->configurationOptions[static::OPTION_DRY_RUN];
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function isExplaining(): bool
    {
        return $this->configurationOptions[static::OPTION_EXPLAIN];
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getSpecificSniffs(): ?string
    {
        return $this->configurationOptions[static::OPTION_SNIFFS];
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function isVerbose(): bool
    {
        return $this->configurationOptions[static::OPTION_VERBOSE];
    }

    /**
     * {@inheritDoc}
     *
     * @throws \InvalidArgumentException
     *
     * @return int
     */
    public function getLevel(): int
    {
        $optionLevel = $this->resolveOptionLevel();
        $levelAllowed = $this->getAllowedLevels();

        if (!in_array($optionLevel, $levelAllowed, true)) {
            throw new InvalidArgumentException(
                sprintf('Level should be in [%s] range', implode(', ', $levelAllowed)),
            );
        }

        return $optionLevel;
    }

    /**
     * @return int
     */
    protected function resolveOptionLevel(): int
    {
        $optionLevel = $this->configurationOptions[static::OPTION_LEVEL];

        if ($optionLevel !== null) {
            return (int)$optionLevel;
        }

        if (isset($this->moduleConfig[static::MODULE_CONFIG_LEVEL])) {
            return $this->moduleConfig[static::MODULE_CONFIG_LEVEL];
        }

        return $this->developmentConfig->getCodeSnifferLevel();
    }

    /**
     * @return array<int>
     */
    protected function getAllowedLevels(): array
    {
        return [
            static::LEVEL_SPRYKER_BASIC,
            static::LEVEL_SPRYKER_STRICT,
        ];
    }
}
