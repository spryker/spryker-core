<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Relation\Reader;

use Generated\Shared\Transfer\ProductRelationResponseTransfer;

interface ProductRelationReaderInterface
{
    /**
     * @param int $idProductRelation
     *
     * @return \Generated\Shared\Transfer\ProductRelationResponseTransfer
     */
    public function findProductRelationById(int $idProductRelation): ProductRelationResponseTransfer;
}
