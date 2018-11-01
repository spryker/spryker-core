<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryFilter\FacetUpdater;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;

interface FacetUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\FacetSearchResultTransfer[]|\Generated\Shared\Transfer\RangeSearchResultTransfer[] $facets
     * @param array $updateCriteria
     *
     * @return \Generated\Shared\Transfer\FacetSearchResultTransfer[]|\Generated\Shared\Transfer\RangeSearchResultTransfer[]
     */
    public function update(array $facets, array $updateCriteria);

    /**
     * @param array $facets
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer[]
     */
    public function updateFromTransfer(array $facets, ProductCategoryFilterTransfer $productCategoryFilterTransfer);
}
