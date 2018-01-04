<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryFilterStorage\Storage;

use Generated\Shared\Transfer\ProductCategoryFilterStorageTransfer;

interface ProductCategoryFilterStorageReaderInterface
{

    /**
     * @param int $idCategory
     *
     * @return ProductCategoryFilterStorageTransfer|null
     */
    public function getProductCategoryFilter($idCategory);
}
