<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup\Business;

interface SetupFacadeInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Method will be removed without replacement.
     *
     * @param array $roles
     *
     * @return string
     */
    public function generateCronjobs(array $roles);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Method will be removed without replacement.
     *
     * @return string
     */
    public function enableJenkins();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Method will be removed without replacement.
     *
     * @return string
     */
    public function disableJenkins();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Use {@link emptyGeneratedDirectory()} instead
     *
     * @return void
     */
    public function removeGeneratedDirectory();

    /**
     * Specification:
     * - Emtpies the configured directory that contains generated files
     *
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return void
     */
    public function emptyGeneratedDirectory();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Hook in commands manually on project level
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getConsoleCommands();
}
