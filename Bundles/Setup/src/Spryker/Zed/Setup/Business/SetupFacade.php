<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Setup\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Setup\Business\SetupBusinessFactory getFactory()
 */
class SetupFacade extends AbstractFacade implements SetupFacadeInterface
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    public function getRepeatData(Request $request)
    {
        return $this->getFactory()->getTransferObjectRepeater()
            ->getRepeatData($request->query->get('mvc', null));
    }

    /**
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getConsoleCommands()
    {
        return $this->getFactory()->getConsoleCommands();
    }

}
