<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication;

use Spryker\Shared\NewRelic\Api;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;

class ApplicationDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return Api
     */
    public function createNewRelicApi()
    {
        return new Api();
    }

}
