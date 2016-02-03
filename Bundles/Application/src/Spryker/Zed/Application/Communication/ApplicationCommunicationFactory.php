<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication;

use Spryker\Shared\NewRelic\Api;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Application\ApplicationConfig getConfig()
 */
class ApplicationCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Shared\NewRelic\Api
     */
    public function createNewRelicApi()
    {
        return new Api();
    }

}
