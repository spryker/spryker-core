<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Search;

use Elastica\Index;
use Spryker\Client\Kernel\AbstractClient;

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
