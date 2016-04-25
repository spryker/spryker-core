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

    /**
     * @api
     *
     * @param int $idCategory
     * @param string $searchString
     * @param array $parameters
     *
     * @return array
     */
    public function categorySearch($idCategory, $searchString = null, array $parameters = [])
    {
        $searchQuery = $this
            ->getFactory()
            ->createCategorySearchQuery($idCategory, $searchString);

        $searchQuery = $this
            ->getFactory()
            ->getSearchClient()
            ->expandQuery($searchQuery, $this->getFactory()->createCatalogSearchQueryExpanderPlugins(), $parameters);

        $resultFormatter = $this
            ->getFactory()
            ->createCatalogSearchResultFormatters();

        return $this
            ->getFactory()
            ->getSearchClient()
            ->search($searchQuery, $resultFormatter, $parameters);
    }

    /**
     * @api
     *
     * @param string $searchString
     * @param array $parameters
     *
     * @return array
     */
    public function fulltextSearch($searchString, array $parameters = [])
    {
        $searchQuery = $this
            ->getFactory()
            ->createFulltextSearchQuery($searchString);

        $searchQuery = $this
            ->getFactory()
            ->getSearchClient()
            ->expandQuery($searchQuery, $this->getFactory()->createCatalogSearchQueryExpanderPlugins(), $parameters);

        $resultFormatter = $this
            ->getFactory()
            ->createCatalogSearchResultFormatters();

        return $this
            ->getFactory()
            ->getSearchClient()
            ->search($searchQuery, $resultFormatter, $parameters);
    }

}
