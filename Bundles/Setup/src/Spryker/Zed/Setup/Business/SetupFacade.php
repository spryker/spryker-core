<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Setup\Business\SetupBusinessFactory getFactory()
 */
class SetupFacade extends AbstractFacade implements SetupFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Method will be removed without replacement.
     *
     * @param array $roles
     *
     * @return string
     */
    public function generateCronjobs(array $roles)
    {
        return $this->getFactory()->createModelCronjobs()->generateCronjobs($roles);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Method will be removed without replacement.
     *
     * @return string
     */
    public function enableJenkins()
    {
        return $this->getFactory()->createModelCronjobs()->enableJenkins();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Method will be removed without replacement.
     *
     * @return string
     */
    public function disableJenkins()
    {
        return $this->getFactory()->createModelCronjobs()->disableJenkins();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link emptyGeneratedDirectory()} instead.
     *
     * @return void
     */
    public function removeGeneratedDirectory()
    {
        $this->getFactory()->createModelGeneratedDirectoryRemover()->execute();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function emptyGeneratedDirectory()
    {
        $this->getFactory()->createGeneratedDirectoryModel()->clear();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Hook in commands manually on project level
     *
     * @return array<\Symfony\Component\Console\Command\Command>
     */
    public function getConsoleCommands()
    {
        return $this->getFactory()->getConsoleCommands();
    }
}
