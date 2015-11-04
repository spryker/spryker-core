<?php
/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\NewRelic\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Shared\NewRelic\Api;
use SprykerFeature\Zed\NewRelic\NewRelicConfig;

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
