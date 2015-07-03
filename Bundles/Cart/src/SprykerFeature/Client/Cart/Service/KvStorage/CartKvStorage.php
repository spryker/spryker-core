<?php

namespace SprykerFeature\Client\Cart\Service\KvStorage;

use SprykerFeature\Client\KvStorage\Service\KvStorageClientInterface;
use SprykerFeature\Shared\ProductFrontendExporterConnector\Code\KeyBuilder\SharedAbstractProductResourceKeyBuilder;

class CartKvStorage extends SharedAbstractProductResourceKeyBuilder implements CartKvStorageInterface
{
    /** @var KvStorageClientInterface **/
    protected $kvStorage;

    public function __construct(KvStorageClientInterface $kvStorage)
    {
        $this->kvStorage = $kvStorage;
    }

    /**
     * @param int $idProduct
     *
     * @return array
     */
    public function getProduct($idProduct)
    {
        $key = $this->generateKey($idProduct, 'de_de');
        return $this->kvStorage->get($key);
    }
}
