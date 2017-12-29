<?php

namespace Spryker\Client\ProductGroupStorage;

use Generated\Shared\Transfer\ProductAbstractGroupStorageTransfer;

interface ProductGroupStorageClientInterface
{
    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return ProductAbstractGroupStorageTransfer
     */
    public function findProductGroupItemsByIdProductAbstract($idProductAbstract, $localeName);
}
