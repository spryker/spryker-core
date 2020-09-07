<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Mapping;

use Elastica\Client;
use Generated\Shared\Transfer\SearchCollectorConfigurationTransfer;

interface MappingFactoryInterface
{
    /**
     * @param \Elastica\Client $elasticaClient
     * @param \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer $searchCollectorConfigurationTransfer
     * @param array $mappingProperties
     *
     * @return \Elastica\Type\Mapping|\Spryker\Zed\Collector\Business\Mapping\MappingAdapterInterface
     */
    public function createMapping(
        Client $elasticaClient,
        SearchCollectorConfigurationTransfer $searchCollectorConfigurationTransfer,
        array $mappingProperties = []
    );
}
