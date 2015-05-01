<?php

namespace SprykerFeature\Zed\Application\Communication;

use SprykerFeature\Zed\Application\Business\ApplicationFacade;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;

class ApplicationDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return ApplicationFacade
     */
    public function getApplicationFacade()
    {
        return $this->getLocator()->application()->facade();
    }
}
