<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelation\Storage;

interface ProductRelationStorageInterface
{
    /**
     * @param int $idAbstractProduct
     *
     * @return \Generated\Shared\Transfer\StorageProductRelationsTransfer[]
     */
    public function getAll($idAbstractProduct);
}
