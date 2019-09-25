<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsPageSearch;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CmsPageSearch\CmsPageSearchFactory getFactory()
 */
class CmsPageSearchClient extends AbstractClient implements CmsPageSearchClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $searchString
     * @param array $requestParameters
     *
     * @return array
     */
    public function search(string $searchString, array $requestParameters = []): array
    {
        $queryExpanderPlugins = $this->getFactory()->getCmsPageSearchQueryExpanderPlugins();

        $searchQuery = $this->getFactory()
            ->createCmsPageSearchQuery($searchString, $requestParameters, $queryExpanderPlugins);

        $resultFormatters = $this
            ->getFactory()
            ->getCmsPageSearchResultFormatters();

        return $this
            ->getFactory()
            ->getSearchClient()
            ->search($searchQuery, $resultFormatters, $requestParameters);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $searchString
     * @param array $requestParameters
     *
     * @return int
     */
    public function searchCount(string $searchString, array $requestParameters): int
    {
        $queryExpanderPlugins = $this->getFactory()->getCmsPageSearchCountQueryExpanderPlugins();

        $searchQuery = $this->getFactory()
            ->createCmsPageSearchQuery($searchString, $requestParameters, $queryExpanderPlugins);

        return $this
            ->getFactory()
            ->getSearchClient()
            ->search($searchQuery, [], $requestParameters)
            ->getTotalHits();
    }
}
