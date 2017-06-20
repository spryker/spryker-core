<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Table;

interface RelatedProductTableQueryBuilderInterface
{

    /**
     * @param int|null $idProductLabel
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function buildAvailableProductQuery($idProductLabel = null);

    /**
     * @param int|null $idProductLabel
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function buildAssignedProductQuery($idProductLabel = null);

}
