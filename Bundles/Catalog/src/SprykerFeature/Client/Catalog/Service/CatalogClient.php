<?php

namespace SprykerFeature\Client\Catalog\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;
use SprykerFeature\Client\Catalog\Service\Model\FacetConfig;
use SprykerFeature\Client\Catalog\Service\Model\FacetSearch;
use SprykerFeature\Client\Catalog\Service\Model\FulltextSearch;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Client\Catalog\Service\Model\Catalog;

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
