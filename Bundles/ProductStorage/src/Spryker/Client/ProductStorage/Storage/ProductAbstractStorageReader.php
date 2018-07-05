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

class ProductAbstractStorageReader implements ProductAbstractStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var \Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductAbstractRestrictionPluginInterface[]
     */
    protected $productAbstractRestrictionPlugins;

    /**
     * @param \Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductAbstractRestrictionPluginInterface[] $productAbstractRestrictionPlugins
     */
    public function __construct(
        ProductStorageToStorageClientInterface $storageClient,
        ProductStorageToSynchronizationServiceInterface $synchronizationService,
        Store $store,
        array $productAbstractRestrictionPlugins = []
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->store = $store;
        $this->productAbstractRestrictionPlugins = $productAbstractRestrictionPlugins;
    }

    /**
     * @deprecated Use findProductAbstractStorageData(int $idProductAbstract, string $localeName): ?array
     *
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array
     */
    public function getProductAbstractStorageData($idProductAbstract, $localeName)
    {
        return $this->findProductAbstractStorageData($idProductAbstract, $localeName);
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageData(int $idProductAbstract, string $localeName): ?array
    {
        if ($this->isProductAbstractRestricted($idProductAbstract)) {
            return null;
        }

        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setReference($idProductAbstract)
            ->setLocale($localeName)
            ->setStore($this->store->getStoreName());

        $key = $this->synchronizationService
            ->getStorageKeyBuilder(ProductStorageConstants::PRODUCT_ABSTRACT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);

        return $this->storageClient->get($key);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductAbstractRestricted(int $idProductAbstract): bool
    {
        foreach ($this->productAbstractRestrictionPlugins as $productAbstractRestrictionPlugin) {
            if ($productAbstractRestrictionPlugin->isRestricted($idProductAbstract)) {
                return true;
            }
        }

        return false;
    }
}
