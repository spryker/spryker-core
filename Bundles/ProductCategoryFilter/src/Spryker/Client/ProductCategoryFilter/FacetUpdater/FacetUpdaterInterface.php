<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryFilter\FacetUpdater;

interface FacetUpdaterInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer[] $facets
     * @param array|null $updateCriteria
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer[]
     */
    public function update($facets, $updateCriteria);
}
