<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup\Business;

interface SetupFacadeInterface
{
    /**
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
     * @api
     *
     * @deprecated Method will be removed without replacement.
     *
     * @return string
     */
    public function enableJenkins();

    /**
     * @api
     *
     * @deprecated Method will be removed without replacement.
     *
     * @return string
     */
    public function disableJenkins();

    /**
     * @api
     *
     * @deprecated Use emptyGeneratedDirectory() instead
     *
     * @return void
     */
    public function removeGeneratedDirectory();

    /**
     * Specification:
     * - Emtpies the configured directory that contains generated files
     *
     * @api
     *
     * @return void
     */
    public function emptyGeneratedDirectory();

    /**
     * @api
     *
     * @deprecated Hook in commands manually on project level
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getConsoleCommands();
}
