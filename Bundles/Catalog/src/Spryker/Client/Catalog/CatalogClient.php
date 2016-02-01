<?php

namespace Spryker\Client\Catalog;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\Catalog\Model\FacetConfig;
use Spryker\Client\Catalog\Model\FacetSearch;
use Spryker\Client\Catalog\Model\FulltextSearch;
use Symfony\Component\HttpFoundation\Request;
use Spryker\Client\Catalog\Model\Catalog;

/**
 * @method CatalogFactory getFactory()
 */
class CatalogClient extends AbstractClient
{

    /**
     * @return \Spryker\Client\Catalog\Model\Catalog
     */
    public function createCatalogModel()
    {
        return $this->getFactory()->createCatalogModel();
    }

    /**
     * @param Request $request
     * @param array $category
     *
     * @return \Spryker\Client\Catalog\Model\FacetSearch
     */
    public function createFacetSearch(Request $request, array $category)
    {
        return $this->getFactory()->createFacetSearch($request, $category);
    }

    /**
     * @param Request $request
     *
     * @return \Spryker\Client\Catalog\Model\FulltextSearch
     */
    public function createFulltextSearch(Request $request)
    {
        return $this->getFactory()->createFulltextSearch($request);
    }

    /**
     * @return \Spryker\Client\Catalog\Model\FacetConfig
     */
    public function createFacetConfig()
    {
        return $this->getFactory()->createFacetConfig();
    }

}
