<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientInterface;
use Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToSynchronizationServiceInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductStorage\ProductStorageConstants;

class ProductConcreteStorageReader implements ProductConcreteStorageReaderInterface
{
    /**
     * @var ProductStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var ProductStorageToStorageClientInterface
     */
    protected $productStorageToStorageClient;

    /**
     * @var Store
     */
    protected $store;

    /**
     * @param ProductStorageToSynchronizationServiceInterface $synchronizationService
     * @param ProductStorageToStorageClientInterface $productStorageToStorageClient
     * @param Store $store
     */
    public function __construct(
        ProductStorageToSynchronizationServiceInterface $synchronizationService,
        ProductStorageToStorageClientInterface $productStorageToStorageClient,
        Store $store
    ) {
        $this->synchronizationService = $synchronizationService;
        $this->productStorageToStorageClient = $productStorageToStorageClient;
        $this->store = $store;
    }

    /**
     * @param int $idProductConcrete
     * @param string $locale
     *
     * @return array
     */
    public function getProductConcreteStorageData($idProductConcrete, $locale)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setStore($this->store->getStoreName())
            ->setLocale($locale)
            ->setReference($idProductConcrete);

        $key = $this->synchronizationService->getStorageKeyBuilder(ProductStorageConstants::PRODUCT_CONCRETE_RESOURCE_NAME)->generateKey($synchronizationDataTransfer);

        return $this->productStorageToStorageClient->get($key);
    }
}
