<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Setup\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method SetupBusinessFactory getBusinessFactory()
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
        return $this->getBusinessFactory()->createModelCronjobs()->generateCronjobs($roles);
    }

    /**
     * @return string
     */
    public function enableJenkins()
    {
        return $this->getBusinessFactory()->createModelCronjobs()->enableJenkins();
    }

    /**
     * @return string
     */
    public function disableJenkins()
    {
        return $this->getBusinessFactory()->createModelCronjobs()->disableJenkins();
    }

    /**
     * @return void
     */
    public function removeGeneratedDirectory()
    {
        $this->getBusinessFactory()->createModelGeneratedDirectoryRemover()->execute();
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function getRepeatData(Request $request)
    {
        return $this->getBusinessFactory()->createTransferObjectRepeater()
            ->getRepeatData($request->query->get('mvc', null));
    }

    /**
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return $this->getBusinessFactory()->getConsoleCommands();
    }

}
