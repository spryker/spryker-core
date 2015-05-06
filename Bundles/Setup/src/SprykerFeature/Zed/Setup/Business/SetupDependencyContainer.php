<?php

namespace SprykerFeature\Zed\Setup\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\SetupBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;


/**
 * @method SetupBusiness getFactory()
 */
class SetupDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return Model\Cronjobs
     */
    public function getModelCronjobs()
    {
        return $this->getFactory()->createModelCronjobs();
    }
}
