<?php
/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\NewRelic\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Shared\NewRelic\Api;
use Spryker\Zed\NewRelic\NewRelicConfig;

/**
 * @method NewRelicConfig getConfig()
 */
class NewRelicCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Shared\NewRelic\Api
     */
    public function createNewRelicApi()
    {
        return new Api();
    }

}
