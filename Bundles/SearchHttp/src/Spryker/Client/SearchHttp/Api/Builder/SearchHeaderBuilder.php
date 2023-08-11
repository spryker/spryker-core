<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api\Builder;

use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface;
use Spryker\Client\SearchHttp\SearchHttpConfig;

class SearchHeaderBuilder implements SearchHeaderBuilderInterface
{
    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    protected const HEADER_STORE_REFERENCE = 'X-Store-Reference';

    /**
     * @var string
     */
    protected const HEADER_TENANT_IDENTIFIER = 'X-Tenant-Identifier';

    /**
     * @var string
     */
    protected const HEADER_ACCEPT_LANGUAGE = 'Accept-Language';

    /**
     * @var \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface
     */
    protected SearchHttpToStoreClientInterface $storeClient;

    /**
     * @var \Spryker\Client\SearchHttp\SearchHttpConfig
     */
    protected SearchHttpConfig $searchHttpConfig;

    /**
     * @param \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface $storeClient
     * @param \Spryker\Client\SearchHttp\SearchHttpConfig $searchHttpConfig
     */
    public function __construct(
        SearchHttpToStoreClientInterface $storeClient,
        SearchHttpConfig $searchHttpConfig
    ) {
        $this->storeClient = $storeClient;
        $this->searchHttpConfig = $searchHttpConfig;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     *
     * @return array<string, string>
     */
    public function build(QueryInterface $searchQuery): array
    {
        return [
            static::HEADER_STORE_REFERENCE => $this->storeClient->getCurrentStore()->getStoreReference(),
            static::HEADER_TENANT_IDENTIFIER => $this->searchHttpConfig->getTenantIdentifier(),
            static::HEADER_ACCEPT_LANGUAGE => $searchQuery->getSearchQuery()->getLocaleOrFail(),
        ];
    }
}
