<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication;

use Spryker\Shared\NewRelic\Api;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Application\ApplicationConfig;

/**
 * @method ApplicationConfig getConfig()
 */
class ApplicationCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return Api
     */
    public function createNewRelicApi()
    {
        return new Api();
    }

}
