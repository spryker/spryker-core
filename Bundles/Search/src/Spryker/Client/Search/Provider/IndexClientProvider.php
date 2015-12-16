<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Search\Provider;

use Elastica\Index;
use Spryker\Shared\Search\Provider\AbstractIndexClientProvider;

class IndexClientProvider extends AbstractIndexClientProvider
{

    /**
     * @return Index
     */
    public function getClient()
    {
        return $this->createZedClient();
    }

}
