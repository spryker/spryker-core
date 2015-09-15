<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Search\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;
use SprykerFeature\Client\ZedRequest\Service\Client\ZedClient;

/**
 * @method SearchDependencyContainer getDependencyContainer()
 */
class SearchClient extends AbstractClient
{

    /**
     * @return ZedClient
     */
    public function getIndexClient()
    {
        return $this->getDependencyContainer()->createIndexClient();
    }

}
