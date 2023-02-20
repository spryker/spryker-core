<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api\Builder;

use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface;

class SearchHeaderBuilder implements SearchHeaderBuilderInterface
{
    /**
     * @var string
     */
    protected const HEADER_STORE_REFERENCE = 'X-Store-Reference';

    /**
     * @var string
     */
    protected const HEADER_ACCEPT_LANGUAGE = 'Accept-Language';

    /**
     * @var \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface
     */
    protected SearchHttpToStoreClientInterface $storeClient;

    /**
     * @param \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface $storeClient
     */
    public function __construct(SearchHttpToStoreClientInterface $storeClient)
    {
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     *
     * @return array<string, string>
     */
    public function build(QueryInterface $searchQuery): array
    {
        return [
            static::HEADER_STORE_REFERENCE => $this->storeClient->getCurrentStore()->getStoreReferenceOrFail(),
            static::HEADER_ACCEPT_LANGUAGE => $searchQuery->getSearchQuery()->getLocaleOrFail(),
        ];
    }
}
