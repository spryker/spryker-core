<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Search;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Search\Provider\IndexClientProvider;

class SearchFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\ZedRequest\Client\ZedClient
     */
    public function createIndexClient()
    {
        return $this->createProviderIndexClientProvider()->getClient();
    }

    /**
     * @return \Spryker\Client\Search\Provider\IndexClientProvider
     */
    protected function createProviderIndexClientProvider()
    {
        return new IndexClientProvider();
    }

}
