<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Catalog\CatalogFactory getFactory()
 */
class CatalogClient extends AbstractClient implements CatalogClientInterface
{

    const DEFAULT_FULL_TEXT_BOOSTED_BOOSTING = 3;

    /**
     * @api
     *
     * @param string $searchString
     * @param array $requestParameters
     *
     * @return array
     */
    public function catalogSearch($searchString, array $requestParameters = [])
    {
        $searchQuery = $this
            ->getFactory()
            ->createCatalogSearchQueryPlugin($searchString, self::DEFAULT_FULL_TEXT_BOOSTED_BOOSTING);

        $searchQuery = $this
            ->getFactory()
            ->getSearchClient()
            ->expandQuery($searchQuery, $this->getFactory()->createCatalogSearchQueryExpanderPlugins(), $requestParameters);

        $resultFormatters = $this
            ->getFactory()
            ->createCatalogSearchResultFormatters();

        return $this
            ->getFactory()
            ->getSearchClient()
            ->search($searchQuery, $resultFormatters, $requestParameters);
    }

}
