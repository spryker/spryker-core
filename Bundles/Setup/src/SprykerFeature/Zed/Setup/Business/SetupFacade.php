<?php

namespace SprykerFeature\Zed\Setup\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method SetupDependencyContainer getDependencyContainer()
 */
class SetupFacade extends AbstractFacade
{

    /**
     * @param array $roles
     * @return mixed
     */
    public function generateCronjobs(array $roles)
    {
        return $this->getDependencyContainer()->getModelCronjobs()->generateCronjobs($roles);
    }

    /**
     * @return string
     */
    public function enableJenkins()
    {
        return $this->getDependencyContainer()->getModelCronjobs()->enableJenkins();
    }

    /**
     * @return string
     */
    public function disableJenkins()
    {
        return $this->getDependencyContainer()->getModelCronjobs()->disableJenkins();
    }

}
