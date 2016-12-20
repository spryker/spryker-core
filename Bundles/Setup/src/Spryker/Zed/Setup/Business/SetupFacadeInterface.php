<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup\Business;

use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Symfony\Component\HttpFoundation\Request;

interface SetupFacadeInterface
{

    /**
     * @api
     *
     * @param array $roles
     *
     * @return string
     */
    public function generateCronjobs(array $roles);

    /**
     * @api
     *
     * @return string
     */
    public function enableJenkins();

    /**
     * @api
     *
     * @return string
     */
    public function disableJenkins();

    /**
     * @api
     *
     * @return void
     */
    public function removeGeneratedDirectory();

    /**
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    public function getRepeatData(Request $request);

    /**
     * @api
     *
     * @deprecated Hook in commands manually on project level
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getConsoleCommands();

    /**
     * @api
     *
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return void
     */
    public function installTestData(MessengerInterface $messenger);

}
