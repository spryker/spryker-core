<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication;

use Spryker\Shared\NewRelic\Api;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

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
