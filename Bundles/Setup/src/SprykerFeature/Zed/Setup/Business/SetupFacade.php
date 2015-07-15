<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Setup\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method SetupDependencyContainer getDependencyContainer()
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
        return $this->getDependencyContainer()->createModelCronjobs()->generateCronjobs($roles);
    }

    /**
     * @return string
     */
    public function enableJenkins()
    {
        return $this->getDependencyContainer()->createModelCronjobs()->enableJenkins();
    }

    /**
     * @return string
     */
    public function disableJenkins()
    {
        return $this->getDependencyContainer()->createModelCronjobs()->disableJenkins();
    }

    public function removeGeneratedDirectory()
    {
        $this->getDependencyContainer()->createModelGeneratedDirectoryRemover()->execute();
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function getRepeatData(Request $request)
    {
        return $this->getDependencyContainer()->createTransferObjectRepeater()
            ->getRepeatData($request->query->get('mvc', null))
        ;
    }

}
