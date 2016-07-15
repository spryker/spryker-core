<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Client\ProductOption\Storage;

use Generated\Shared\Transfer\ProductOptionGroupsTransfer;

interface ProductOptionStorageInterface
{
    /**
     * @param int $idAbstractProduct
     *
     * @return ProductOptionGroupsTransfer
     */
    public function get($idAbstractProduct);
}
