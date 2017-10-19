<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryFilter;

interface ProductCategoryFilterClientInterface
{
    /**
     * Specification:
     * - Reads resource from storage based on categoryId and locale
     * - Formats (re-orders/removes) facets based on returned configuration
     * - Returns formatted facets based on category filters saved in database
     *
     * @api
     *
     * @param array $facets
     * @param int $categoryId
     * @param string $localeName
     *
     * @return array
     */
    public function updateFacetsByCategory($facets, $categoryId, $localeName);
}
