<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductGroup\Storage;

use Generated\Shared\Transfer\ProductAbstractGroupsTransfer;

interface ProductGroupStorageReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractGroupsTransfer $productAbstractGroupsTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer[]
     */
    public function findProductGroups(ProductAbstractGroupsTransfer $productAbstractGroupsTransfer, $localeName);
}
