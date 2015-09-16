<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Search\Service;

use Elastica\Index;
use SprykerEngine\Client\Kernel\Service\AbstractClient;

/**
 * @method SearchDependencyContainer getDependencyContainer()
 */
class SearchClient extends AbstractClient
{

    /**
     * @return Index
     */
    public function getIndexClient()
    {
        return $this->getDependencyContainer()->createIndexClient();
    }

}
