<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Relation\Updater;

use Generated\Shared\Transfer\ProductRelationTransfer;

interface RelatedProductUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return void
     */
    public function updateAllRelatedProducts(ProductRelationTransfer $productRelationTransfer): void;
}
