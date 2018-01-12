<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryFilterStorage;

interface ProductCategoryFilterStorageClientInterface
{
    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterStorageTransfer|null
     */
    public function getProductCategoryFilterByIdCategory($idCategory);
}
