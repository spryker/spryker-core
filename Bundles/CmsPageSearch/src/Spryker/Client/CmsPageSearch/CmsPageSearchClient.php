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
     * {@inheritdoc}
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
        $searchClient = $this
            ->getFactory()
            ->getSearchClient();

        $searchQuery = $this
            ->getFactory()
            ->createCmsPageSearchQuery($searchString);

        $searchQuery = $searchClient
            ->expandQuery($searchQuery, $this->getFactory()->getCmsPageSearchQueryExpanderPlugins(), $requestParameters);

        $resultFormatters = $this
            ->getFactory()
            ->getCmsPageSearchResultFormatters();

        return $searchClient->search($searchQuery, $resultFormatters, $requestParameters);
    }
}
