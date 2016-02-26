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
     * @param array $parameters
     *
     * @return mixed
     */
    public function categorySearch($idCategory, array $parameters)
    {
        $searchQuery = $this
            ->getFactory()
            ->createCategorySearchQuery($idCategory, $parameters);

        $resultFormatter = $this
            ->getFactory()
            ->createCatalogSearchResultFormatter($parameters);

        return $this
            ->getFactory()
            ->getSearchClient()
            ->search($searchQuery, $resultFormatter);
    }

    /**
     * @api
     *
     * @param string $searchString
     * @param array $parameters
     *
     * @return mixed
     */
    public function fulltextSearch($searchString, array $parameters = [])
    {
        $searchQuery = $this
            ->getFactory()
            ->createFulltextSearchQuery($searchString, $parameters);

        $resultFormatter = $this
            ->getFactory()
            ->createCatalogSearchResultFormatter($parameters);

        return $this
            ->getFactory()
            ->getSearchClient()
            ->search($searchQuery, $resultFormatter);
    }

    /**
     * @api
     *
     * @return \Spryker\Client\Catalog\Model\FacetConfig
     */
    public function createFacetConfig()
    {
        return $this->getFactory()->createFacetConfig();
    }

}
