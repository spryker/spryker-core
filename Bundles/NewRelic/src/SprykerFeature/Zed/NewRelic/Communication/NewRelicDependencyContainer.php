<?php
/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\NewRelic\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Spryker\Shared\NewRelic\Api;
use Spryker\Zed\NewRelic\NewRelicConfig;

/**
 * @method NewRelicConfig getConfig()
 */
class NewRelicDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return Api
     */
    public function createNewRelicApi()
    {
        return new Api();
    }

}
