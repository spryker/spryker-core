<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Search\Dependency\Client;

class SearchToSearchClientBridge implements SearchToSearchClientInterface
{
    /**
     * @var \Spryker\Client\Search\SearchClientInterface
     */
    protected $searchClient;

    /**
     * @param \Spryker\Client\Search\SearchClientInterface
     */
    public function __construct($searchClient)
    {
        $this->searchClient = $searchClient;
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function checkConnection()
    {
        $this->searchClient->checkConnection();
    }


}
