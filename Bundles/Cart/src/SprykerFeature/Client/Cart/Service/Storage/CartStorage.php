<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Cart\Service\Storage;

use SprykerFeature\Client\Storage\Service\StorageClientInterface;
use SprykerFeature\Shared\ProductFrontendExporterConnector\Code\KeyBuilder\SharedAbstractProductResourceKeyBuilder;

class CartStorage extends SharedAbstractProductResourceKeyBuilder implements CartStorageInterface
{

    /**
     * @var StorageClientInterface
     */
    protected $storage;

    /**
     * @param StorageClientInterface $storage
     */
    public function __construct(StorageClientInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param int $idProduct
     *
     * @return array
     */
    public function getProduct($idProduct)
    {
        $key = $this->generateKey($idProduct, 'de_de');

        return $this->storage->get($key);
    }

}
