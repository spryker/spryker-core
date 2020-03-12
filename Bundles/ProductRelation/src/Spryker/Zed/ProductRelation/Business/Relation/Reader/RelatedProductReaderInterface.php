<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Relation\Reader;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Generator;

interface RelatedProductReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generator|\Generated\Shared\Transfer\ProductAbstractTransfer[][]
     */
    public function getRelatedProducts(ProductRelationTransfer $productRelationTransfer): Generator;
}
