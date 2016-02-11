<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Catalog;

use Symfony\Component\HttpFoundation\Request;

interface CatalogClientInterface
{

    /**
     * @return \Spryker\Client\Catalog\Model\Catalog
     */
    public function createCatalogModel();

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $category
     *
     * @return \Spryker\Client\Catalog\Model\FacetSearch
     */
    public function createFacetSearch(Request $request, array $category);

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Client\Catalog\Model\FulltextSearch
     */
    public function createFulltextSearch(Request $request);

    /**
     * @return \Spryker\Client\Catalog\Model\FacetConfig
     */
    public function createFacetConfig();

}
