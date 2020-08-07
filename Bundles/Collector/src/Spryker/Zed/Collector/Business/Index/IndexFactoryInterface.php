<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Index;

use Elastica\Client;
use Generated\Shared\Transfer\SearchCollectorConfigurationTransfer;

interface IndexFactoryInterface
{
    /**
     * @param \Elastica\Client $elasticaClient
     * @param \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer $searchCollectorConfigurationTransfer
     *
     * @return \Elastica\Index|\Spryker\Zed\Collector\Business\Index\IndexAdapterInterface
     */
    public function createIndex(Client $elasticaClient, SearchCollectorConfigurationTransfer $searchCollectorConfigurationTransfer);
}
