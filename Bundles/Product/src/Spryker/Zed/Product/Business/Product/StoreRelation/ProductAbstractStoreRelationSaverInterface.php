<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\StoreRelation;

use Generated\Shared\Transfer\StoreRelationTransfer;

interface ProductAbstractStoreRelationSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    public function save(StoreRelationTransfer $storeRelationTransfer);
}
