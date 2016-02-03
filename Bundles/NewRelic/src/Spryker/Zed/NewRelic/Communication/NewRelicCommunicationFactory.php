<?php
/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\NewRelic\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Shared\NewRelic\Api;

/**
 * @method \Spryker\Zed\NewRelic\NewRelicConfig getConfig()
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
