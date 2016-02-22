<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup\Business;

use Symfony\Component\HttpFoundation\Request;

interface SetupFacadeInterface
{

    /**
     * @param array $roles
     *
     * @return mixed
     */
    public function generateCronjobs(array $roles);

    /**
     * @return string
     */
    public function enableJenkins();

    /**
     * @return string
     */
    public function disableJenkins();

    /**
     * @return void
     */
    public function removeGeneratedDirectory();

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    public function getRepeatData(Request $request);

    /**
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getConsoleCommands();

}
