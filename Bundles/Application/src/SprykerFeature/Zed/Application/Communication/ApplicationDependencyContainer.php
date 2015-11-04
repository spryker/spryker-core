<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication;

use SprykerFeature\Shared\NewRelic\Api;
use SprykerFeature\Zed\Application\Business\ApplicationFacade;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;

class ApplicationDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return ApplicationFacade
     */
    public function getApplicationFacade()
    {
        return $this->getLocator()->application()->facade();
    }

    /**
     * @return Api
     */
    public function createNewRelicApi()
    {
        return new Api();
    }

}
