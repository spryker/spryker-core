<?php

namespace SprykerFeature\Client\Catalog;

use SprykerEngine\Client\Kernel\AbstractClient;
use SprykerFeature\Client\Catalog\Model\FacetConfig;
use SprykerFeature\Client\Catalog\Model\FacetSearch;
use SprykerFeature\Client\Catalog\Model\FulltextSearch;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Client\Catalog\Model\Catalog;

/**
 * @method CatalogDependencyContainer getDependencyContainer()
 */
class CatalogClient extends AbstractClient
{

    /**
     * @return Catalog
     */
    public function createCatalogModel()
    {
        return $this->getDependencyContainer()->createCatalogModel();
    }

    /**
     * @param Request $request
     * @param array $category
     *
     * @return FacetSearch
     */
    public function createFacetSearch(Request $request, array $category)
    {
        return $this->getDependencyContainer()->createFacetSearch($request, $category);
    }

    /**
     * @param Request $request
     *
     * @return FulltextSearch
     */
    public function createFulltextSearch(Request $request)
    {
        return $this->getDependencyContainer()->createFulltextSearch($request);
    }

    /**
     * @return FacetConfig
     */
    public function createFacetConfig()
    {
        return $this->getDependencyContainer()->createFacetConfig();
    }

}
