<?php

namespace SprykerFeature\Zed\Setup\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method SetupDependencyContainer getDependencyContainer()
 */
class SetupFacade extends AbstractFacade
{

    /**
     * @return string
     */
    public function generateCronjobs()
    {
        return $this->getDependencyContainer()->getModelCronjobs()->generateCronjobs();
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
