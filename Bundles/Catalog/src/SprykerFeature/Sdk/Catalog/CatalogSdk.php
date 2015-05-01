<?php

namespace SprykerFeature\Sdk\Catalog;

use SprykerEngine\Sdk\Kernel\AbstractSdk;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CatalogDependencyContainer getDependencyContainer()
 */
class CatalogSdk extends AbstractSdk
{
    /**
     * @return \SprykerFeature\Sdk\Catalog\Model\Catalog
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
     * @return \Pyz\Sdk\Catalog\Model\FacetConfig
     */
    public function createFacetConfig()
    {
        return $this->getDependencyContainer()->createFacetConfig();
    }
}
