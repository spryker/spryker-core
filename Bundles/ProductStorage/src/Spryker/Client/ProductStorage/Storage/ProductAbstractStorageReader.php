<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientInterface;
use Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductStorage\Filter\ProductAbstractAttributeMapRestrictionFilterInterface;
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
     * @var \Spryker\Client\ProductStorage\Filter\ProductAbstractAttributeMapRestrictionFilterInterface
     */
    protected $productAbstractVariantsRestrictionFilter;

    /**
     * @param \Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Client\ProductStorage\Filter\ProductAbstractAttributeMapRestrictionFilterInterface $productAbstractVariantsRestrictionFilter
     * @param \Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductAbstractRestrictionPluginInterface[] $productAbstractRestrictionPlugins
     */
    public function __construct(
        ProductStorageToStorageClientInterface $storageClient,
        ProductStorageToSynchronizationServiceInterface $synchronizationService,
        Store $store,
        ProductAbstractAttributeMapRestrictionFilterInterface $productAbstractVariantsRestrictionFilter,
        array $productAbstractRestrictionPlugins = []
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->store = $store;
        $this->productAbstractVariantsRestrictionFilter = $productAbstractVariantsRestrictionFilter;
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

        $key = $this->getStorageKey($idProductAbstract, $localeName);

        $productStorageData = $this->storageClient->get($key);

        if (!$productStorageData) {
            return null;
        }

        $productStorageData = $this->productAbstractVariantsRestrictionFilter
            ->filterAbstractProductVariantsData($productStorageData);

        return $productStorageData;
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

    /**
     * @param string $mappingType
     * @param string $identifier
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageDataByMapping(string $mappingType, string $identifier, string $localeName): ?array
    {
        $reference = $mappingType . ':' . $identifier;
        $mappingKey = $this->getStorageKey($reference, $localeName);
        $mappingData = $this->storageClient->get($mappingKey);

        if (!$mappingData) {
            return null;
        }

        return $this->findProductAbstractStorageData($mappingData['id'], $localeName);
    }

    /**
     * @param string $reference
     * @param string $locale
     *
     * @return string
     */
    protected function getStorageKey(string $reference, string $locale): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setReference($reference)
            ->setLocale($locale)
            ->setStore($this->store->getStoreName());

        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductStorageConstants::PRODUCT_ABSTRACT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
