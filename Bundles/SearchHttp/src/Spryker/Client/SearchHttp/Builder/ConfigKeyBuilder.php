<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Builder;

use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface;
use Spryker\Shared\SearchHttp\SearchHttpConfig;

class ConfigKeyBuilder implements ConfigKeyBuilderInterface
{
    /**
     * @var \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface
     */
    protected SearchHttpToStoreClientInterface $searchHttpToStoreClient;

    /**
     * @param \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface $searchHttpToStoreClient
     */
    public function __construct(
        SearchHttpToStoreClientInterface $searchHttpToStoreClient
    ) {
        $this->searchHttpToStoreClient = $searchHttpToStoreClient;
    }

    /**
     * @return string
     */
    public function buildKeyForCurrentStore(): string
    {
        return strtolower(
            implode(
                ':',
                [
                    SearchHttpConfig::SEARCH_HTTP_CONFIG_RESOURCE_NAME,
                    $this->searchHttpToStoreClient->getCurrentStore()->getName(),
                ],
            ),
        );
    }
}
