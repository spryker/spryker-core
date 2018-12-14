<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\CodeStyleSniffer\Config;

interface CodeStyleSnifferConfigurationInterface
{
    /**
     * @param array $moduleConfig
     *
     * @return void
     */
    public function setModuleConfig(array $moduleConfig): void;

    /**
     * @param array $configurationOptions
     *
     * @return void
     */
    public function setConfigurationOptions(array $configurationOptions): void;

    /**
     * @return string
     */
    public function getCodingStandard(): string;

    /**
     * @return string|null
     */
    public function getOptionIgnore(): ?string;

    /**
     * @return bool
     */
    public function getOptionFix(): bool;

    /**
     * This option responsible for showing or not the progressing bar for `phpcs` command.
     *
     * @return bool
     */
    public function getOptionQuiet(): bool;

    /**
     * @return bool
     */
    public function getOptionDryRun(): bool;

    /**
     * @return bool
     */
    public function getOptionExplain(): bool;

    /**
     * @return string|null
     */
    public function getOptionSniffs(): ?string;

    /**
     * @return bool
     */
    public function getOptionVerbose(): bool;

    /**
     * @return int
     */
    public function getOptionLevel(): int;
}
