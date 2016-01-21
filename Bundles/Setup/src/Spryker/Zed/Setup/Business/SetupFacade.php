<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Setup\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method SetupBusinessFactory getFactory()
 */
class SetupFacade extends AbstractFacade
{

    /**
     * @param array $roles
     *
     * @return mixed
     */
    public function generateCronjobs(array $roles)
    {
        return $this->getFactory()->createModelCronjobs()->generateCronjobs($roles);
    }

    /**
     * @return string
     */
    public function enableJenkins()
    {
        return $this->getFactory()->createModelCronjobs()->enableJenkins();
    }

    /**
     * @return string
     */
    public function disableJenkins()
    {
        return $this->getFactory()->createModelCronjobs()->disableJenkins();
    }

    /**
     * @return void
     */
    public function removeGeneratedDirectory()
    {
        $this->getFactory()->createModelGeneratedDirectoryRemover()->execute();
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function getRepeatData(Request $request)
    {
        return $this->getFactory()->getTransferObjectRepeater()
            ->getRepeatData($request->query->get('mvc', null));
    }

    /**
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return $this->getFactory()->getConsoleCommands();
    }

}
