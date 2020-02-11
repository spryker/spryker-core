<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SearchElasticsearch\ElasticaClient;

use Elastica\Client;

interface ElasticaClientFactoryInterface
{
    /**
     * @param array $clientConfig
     *
     * @return \Elastica\Client
     */
    public function createClient(array $clientConfig): Client;
}
