<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\ProductAbstractRelation;

interface ProductAbstractRelationDeleterInterface
{
    /**
     * @param int $idProductLabel
     * @param array<int> $productAbstractIds
     * @param bool $isTouchEnabled
     *
     * @return void
     */
    public function removeRelations($idProductLabel, array $productAbstractIds, bool $isTouchEnabled = true);
}
