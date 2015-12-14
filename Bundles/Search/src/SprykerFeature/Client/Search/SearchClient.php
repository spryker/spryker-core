<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Search;

use Elastica\Index;
use SprykerEngine\Client\Kernel\AbstractClient;

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
