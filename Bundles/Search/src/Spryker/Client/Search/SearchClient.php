<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Search;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class SearchClient extends AbstractClient implements SearchClientInterface
{

    /**
     * @return \Elastica\Index
     */
    public function getIndexClient()
    {
        return $this->getFactory()->createIndexClient();
    }

}
