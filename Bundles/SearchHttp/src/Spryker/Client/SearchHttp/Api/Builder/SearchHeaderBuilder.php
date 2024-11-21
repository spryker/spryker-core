<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api\Builder;

use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchHttp\SearchHttpConfig;

class SearchHeaderBuilder implements SearchHeaderBuilderInterface
{
    /**
     * @var \Spryker\Client\SearchHttp\SearchHttpConfig
     */
    protected SearchHttpConfig $searchHttpConfig;

    /**
     * @param \Spryker\Client\SearchHttp\SearchHttpConfig $searchHttpConfig
     */
    public function __construct(SearchHttpConfig $searchHttpConfig)
    {
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
            'Accept-Language' => $searchQuery->getSearchQuery()->getLocaleOrFail(),
            'User-Agent' => sprintf('Spryker/%s', APPLICATION),
            'X-Forwarded-For' => $this->searchHttpConfig->getForwardForAddress(),
        ];
    }
}
