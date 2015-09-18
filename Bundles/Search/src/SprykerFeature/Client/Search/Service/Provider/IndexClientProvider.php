<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Search\Service\Provider;

use Elastica\Index;
use SprykerFeature\Shared\Search\Provider\AbstractIndexClientProvider;

class IndexClientProvider extends AbstractIndexClientProvider
{

    /**
     * @return Index
     */
    public function getClient()
    {
        return $this->createClient();
    }

}
