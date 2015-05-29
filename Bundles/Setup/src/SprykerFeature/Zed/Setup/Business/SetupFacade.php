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

    public function cleanPropelSchemaDirectory()
    {
        $this->getDependencyContainer()->createModelPropelSchema()->cleanTargetDirectory();
    }

    public function copySchemaFilesToTargetDirectory()
    {
        $this->getDependencyContainer()->createModelPropelSchema()->copyToTargetDirectory();
    }

}
