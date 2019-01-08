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
     * @return $this
     */
    public function setModuleConfig(array $moduleConfig);

    /**
     * @param array $configurationOptions
     *
     * @return $this
     */
    public function setConfigurationOptions(array $configurationOptions);

    /**
     * Returns the list of paths to the coding standards which should be used in current CodeStyleSniffer run.
     * Multiple paths should be separated by coma.
     *
     * @return string
     */
    public function getCodingStandard(): string;

    /**
     * Returns the list of patterns to the files or folders which should be skipped in current CodeStyleSniffer run.
     * Patterns are treated as regular expressions. Multiple paths should be separated by coma.
     *
     * @return string|null
     */
    public function getIgnoredPaths(): ?string;

    /**
     * Returns true if current CodeStyleSniffer run should fix all the fixable errors.
     *
     * @return bool
     */
    public function isFixing(): bool;

    /**
     * Returns true if current CodeStyleSniffer run should display the progress of execution via the progress bar.
     *
     * @return bool
     */
    public function isQuiet(): bool;

    /**
     * Returns true if current CodeStyleSniffer run should only reveal the `phpcs` command without execution.
     *
     * @return bool
     */
    public function isDryRun(): bool;

    /**
     * Returns true if current CodeStyleSniffer run should show all the sniffs it includes.
     *
     * @return bool
     */
    public function isExplaining(): bool;

    /**
     * Returns the list to paths to the sniffs to limit the standard passed in @see getCodingStandard().
     * Only those sniffs will be executed in current CodeStyleSniffer run.
     *
     * @return string|null
     */
    public function getSpecificSniffs(): ?string;

    /**
     * Returns true if current CodeStyleSniffer run should be more verbose:
     * - Displays a sniff code which cause an error
     * - Displays current processing directory and file
     * - Displays results of the file's sniffing
     *
     * @return bool
     */
    public function isVerbose(): bool;

    /**
     * Returns the level of current CodeStyleSniffer run.
     * Each level provides the different standard(s) for `phpcs` command.
     *
     * @return int
     */
    public function getLevel(): int;
}
