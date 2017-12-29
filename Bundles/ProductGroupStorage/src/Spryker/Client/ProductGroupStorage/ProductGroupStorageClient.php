<?php

namespace Spryker\Client\ProductGroupStorage;

use Generated\Shared\Transfer\ProductAbstractGroupStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductGroupStorage\ProductGroupStorageFactory getFactory()
 */
class ProductGroupStorageClient extends AbstractClient implements ProductGroupStorageClientInterface
{

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return ProductAbstractGroupStorageTransfer
     */
    public function findProductGroupItemsByIdProductAbstract($idProductAbstract, $localeName)
    {
        return $this->getFactory()
            ->createProductGroupStorage()
            ->findProductGroupItemsByIdProductAbstract($idProductAbstract, $localeName);
    }

}
