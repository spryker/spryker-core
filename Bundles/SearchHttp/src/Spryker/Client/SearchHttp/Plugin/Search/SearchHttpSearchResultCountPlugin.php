<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Plugin\Search;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchResultCountPluginInterface;

/**
 * @method \Spryker\Client\SearchHttp\SearchHttpClientInterface getClient()
 * @method \Spryker\Client\SearchHttp\SearchHttpFactory getFactory()
 */
class SearchHttpSearchResultCountPlugin extends AbstractPlugin implements SearchResultCountPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates whether the given search results have the format of an array.
     * - Returns the total count of search results if such a key exists within the given data.
     * - Returns NULL if any of the conditions stated above is false.
     *
     * @api
     *
     * @param mixed $searchResult
     *
     * @return int|null
     */
    public function findTotalCount(mixed $searchResult): ?int
    {
        return $this->getClient()->findSearchResultTotalCount($searchResult);
    }
}
