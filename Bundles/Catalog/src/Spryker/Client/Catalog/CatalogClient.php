<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog;

use Spryker\Client\Kernel\AbstractClient;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Client\Catalog\CatalogFactory getFactory()
 */
class CatalogClient extends AbstractClient implements CatalogClientInterface
{

    /**
     * @return \Spryker\Client\Catalog\Model\Catalog
     */
    public function createCatalogModel()
    {
        return $this->getFactory()->createCatalogModel();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $category
     *
     * @return \Spryker\Client\Catalog\Model\FacetSearch
     */
    public function createFacetSearch(Request $request, array $category)
    {
        return $this->getFactory()->createFacetSearch($request, $category);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
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
