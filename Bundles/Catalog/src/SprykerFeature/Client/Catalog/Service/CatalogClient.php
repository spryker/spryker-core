<?php

namespace SprykerFeature\Client\Catalog\Service;

use SprykerEngine\Client\Kernel\AbstractClient;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CatalogDependencyContainer getDependencyContainer()
 */
class CatalogClient extends AbstractClient
{
    /**
     * @return \SprykerFeature\Client\Catalog\Model\Catalog
     */
    public function createCatalogModel()
    {
        return $this->getDependencyContainer()->createCatalogModel();
    }

    /**
     * @param Request $request
     * @param array $category
     * @return Model\FacetSearch
     */
    public function createFacetSearch(Request $request, array $category)
    {
        return $this->getDependencyContainer()->createFacetSearch($request, $category);
    }

    /**
     * @param Request $request
     * @return Model\FulltextSearch
     */
    public function createFulltextSearch(Request $request)
    {
        return $this->getDependencyContainer()->createFulltextSearch($request);
    }

    /**
     * @return \Pyz\Client\Catalog\Model\FacetConfig
     */
    public function createFacetConfig()
    {
        return $this->getDependencyContainer()->createFacetConfig();
    }
}
