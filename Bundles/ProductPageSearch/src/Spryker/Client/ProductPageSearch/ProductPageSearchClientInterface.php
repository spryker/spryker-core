<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPageSearch;

use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;

interface ProductPageSearchClientInterface
{
    /**
     * Specification:
     * - Finds concrete products at Elasticsearch.
     * - Filters results by searchString and locale.
     * - Limit and offset can be specified.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return array
     */
    public function searchProductConcretesByFullText(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer): array;
}
