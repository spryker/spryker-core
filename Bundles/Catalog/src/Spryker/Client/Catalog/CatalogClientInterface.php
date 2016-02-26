<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog;

interface CatalogClientInterface
{

    /**
     * @api
     *
     * @param int $idCategory
     * @param array $parameters
     *
     * @return mixed
     */
    public function categorySearch($idCategory, array $parameters);

    /**
     * @api
     *
     * @param string $searchString
     * @param array $parameters
     *
     * @return mixed
     */
    public function fulltextSearch($searchString, array $parameters = []);

    /**
     * @api
     *
     * @return \Spryker\Client\Catalog\Model\FacetConfig
     */
    public function createFacetConfig();

}
