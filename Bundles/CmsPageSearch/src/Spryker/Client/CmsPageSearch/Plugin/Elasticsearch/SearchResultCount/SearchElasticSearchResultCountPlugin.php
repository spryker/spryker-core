<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Client\CmsPageSearch\Plugin\Elasticsearch\SearchResultCount;

use Elastica\ResultSet;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchResultCountPluginInterface;

class SearchElasticSearchResultCountPlugin implements SearchResultCountPluginInterface
{
    /**
     * @param mixed $searchResult
     *
     * @return int|null
     */
    public function findTotalCount(mixed $searchResult): ?int
    {
        if (!$searchResult instanceof ResultSet) {
            return null;
        }

        return $searchResult->getTotalHits();
    }
}
