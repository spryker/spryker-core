<?php

namespace Spryker\Client\ProductCategoryFilterStorage;

use Generated\Shared\Transfer\ProductCategoryFilterStorageTransfer;

interface ProductCategoryFilterStorageClientInterface
{

    /**
     * @param int $idCategory
     *
     * @return ProductCategoryFilterStorageTransfer|null
     */
    public function getProductCategoryFilterByIdCategory($idCategory);
}
