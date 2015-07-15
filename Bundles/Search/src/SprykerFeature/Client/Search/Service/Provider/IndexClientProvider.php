<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Search\Service\Provider;

use SprykerFeature\Shared\Search\Provider\AbstractIndexClientProvider;

class IndexClientProvider extends AbstractIndexClientProvider
{

    public function getClient()
    {
        return $this->createClient();
    }

}
