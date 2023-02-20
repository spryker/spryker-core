<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api\Builder;

use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

interface SearchQueryBuilderInterface
{
    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     *
     * @return array<string, mixed>
     */
    public function build(QueryInterface $searchQuery): array;
}
