<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog;

use Symfony\Component\HttpFoundation\Request;

interface CatalogClientInterface
{

    /**
     * @api
     *
     * @return \Spryker\Client\Catalog\Model\Catalog
     */
    public function createCatalogModel();

    /**
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $category
     *
     * @return \Spryker\Client\Catalog\Model\FacetSearch
     */
    public function createFacetSearch(Request $request, array $category);

    /**
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Client\Catalog\Model\FulltextSearch
     */
    public function createFulltextSearch(Request $request);

    /**
     * @api
     *
     * @return \Spryker\Client\Catalog\Model\FacetConfig
     */
    public function createFacetConfig();

}
