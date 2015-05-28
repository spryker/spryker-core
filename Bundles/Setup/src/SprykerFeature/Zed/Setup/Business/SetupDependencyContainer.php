<?php

namespace SprykerFeature\Zed\Setup\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\SetupBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Setup\Business\Model\Cronjobs;
use SprykerFeature\Zed\Setup\Business\Model\GeneratedDirectoryRemover;
use SprykerFeature\Zed\Setup\SetupConfig;

/**
 * @method SetupConfig getConfig()
 * @method SetupBusiness getFactory()
 */
class SetupDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return Cronjobs
     */
    public function createModelCronjobs()
    {
        $config = $this->getConfig();

        return $this->getFactory()->createModelCronjobs($config);
    }

    /**
     * @return GeneratedDirectoryRemover
     */
    public function createModelGeneratedDirectoryRemover()
    {
        return $this->getFactory()->createModelGeneratedDirectoryRemover(
            $this->getConfig()->getGeneratedDirectory()
        );
    }
}
