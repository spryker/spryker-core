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
     * @param string $searchString
     * @param array $requestParameters
     * @param int $fullTextBoostedBoosting
     *
     * @return array
     */
    public function catalogSearch($searchString, array $requestParameters, $fullTextBoostedBoosting);

}
