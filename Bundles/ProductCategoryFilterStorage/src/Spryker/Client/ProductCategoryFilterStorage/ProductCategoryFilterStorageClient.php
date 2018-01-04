<?php

namespace Spryker\Client\ProductCategoryFilterStorage;

use Generated\Shared\Transfer\ProductCategoryFilterStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductCategoryFilterStorage\ProductCategoryFilterStorageFactory getFactory()
 */
class ProductCategoryFilterStorageClient extends AbstractClient implements ProductCategoryFilterStorageClientInterface
{

    /**
     * @param int $idCategory
     *
     * @return ProductCategoryFilterStorageTransfer|null
     */
    public function getProductCategoryFilterByIdCategory($idCategory)
    {
        return $this->getFactory()
            ->createProductCategoryFilterStorageReader()
            ->getProductCategoryFilter($idCategory);
    }

}
