<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOption\Storage;

interface ProductOptionStorageInterface
{
    /**
     * @param int $idAbstractProduct
     *
     * @return \Generated\Shared\Transfer\StorageProductOptionGroupCollectionTransfer
     */
    public function get($idAbstractProduct);
}
