<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Asset\Compatibility;

use ArrayObject;
use Generated\Shared\Transfer\SspAssetStorageConditionsTransfer;
use Generated\Shared\Transfer\SspAssetStorageCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetStorageTransfer;
use Generated\Shared\Transfer\SspModelStorageCollectionTransfer;
use Generated\Shared\Transfer\SspModelStorageConditionsTransfer;
use Generated\Shared\Transfer\SspModelStorageCriteriaTransfer;
use Generated\Shared\Transfer\SspModelStorageTransfer;
use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Client\ProductListStorage\ProductListStorageClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use SprykerFeature\Client\SelfServicePortal\Storage\Reader\SspAssetStorageReaderInterface;
use SprykerFeature\Client\SelfServicePortal\Storage\Reader\SspModelStorageReaderInterface;

class AssetProductCompatibilityChecker implements AssetProductCompatibilityCheckerInterface
{
    public function __construct(
        protected SspAssetStorageReaderInterface $sspAssetStorageReader,
        protected SspModelStorageReaderInterface $sspModelStorageReader,
        protected ProductListStorageClientInterface $productListStorageClient,
        protected CompanyUserClientInterface $companyUserClient,
        protected ProductStorageClientInterface $productStorageClient,
        protected LocaleClientInterface $localeClient
    ) {
    }

    public function getAssetProductCompatibilityMatrix(array $assetReferences, array $skus): array
    {
        if (!$assetReferences || !$skus) {
            return [];
        }

        $skuToIdMap = $this->getProductIdsBySkus($skus);

        $assetStorages = $this->getAssetStoragesByReferences($assetReferences);

        $compatibilityMatrix = [];
        foreach ($assetReferences as $assetReference) {
            $compatibilityMatrix[$assetReference] = [];
            foreach ($skus as $sku) {
                $productId = $skuToIdMap[$sku] ?? null;
                $assetStorage = $assetStorages[$assetReference] ?? null;

                $compatibilityMatrix[$assetReference][$sku] = $this->checkCompatibility(
                    $assetStorage,
                    $productId,
                );
            }
        }

        return $compatibilityMatrix;
    }

    public function isAssetCompatibleToProductSku(string $assetReference, string $sku): bool
    {
        if (!$assetReference || !$sku) {
            return false;
        }

        $productId = $this->getProductIdBySku($sku);
        if (!$productId) {
            return false;
        }

        $assetStorage = $this->findAssetStorageByReference($assetReference);
        if (!$assetStorage) {
            return false;
        }

        return $this->checkCompatibility($assetStorage, $productId);
    }

    /**
     * @param array<string> $skus
     *
     * @return array<string, int>
     */
    protected function getProductIdsBySkus(array $skus): array
    {
        $locale = $this->localeClient->getCurrentLocale();
        $productIdMap = [];

        foreach ($skus as $sku) {
            $productConcreteStorageData = $this->productStorageClient
                ->findProductConcreteStorageDataByMapping('sku', $sku, $locale);

            if ($productConcreteStorageData && isset($productConcreteStorageData['id_product_concrete'])) {
                $productIdMap[$sku] = $productConcreteStorageData['id_product_concrete'];
            }
        }

        return $productIdMap;
    }

    protected function getProductIdBySku(string $sku): ?int
    {
        $productConcreteStorageData = $this->productStorageClient
            ->findProductConcreteStorageDataByMapping('sku', $sku, $this->localeClient->getCurrentLocale());

        if (!$productConcreteStorageData || !isset($productConcreteStorageData['id_product_concrete'])) {
            return null;
        }

        return $productConcreteStorageData['id_product_concrete'];
    }

    /**
     * @param array<string> $assetReferences
     *
     * @return array<string, \Generated\Shared\Transfer\SspAssetStorageTransfer>
     */
    protected function getAssetStoragesByReferences(array $assetReferences): array
    {
        $sspAssetStorageConditions = new SspAssetStorageConditionsTransfer();
        foreach ($assetReferences as $assetReference) {
            $sspAssetStorageConditions->addReference($assetReference);
        }

        $assetStorageCriteriaTransfer = (new SspAssetStorageCriteriaTransfer())
            ->setCompanyUser($this->companyUserClient->findCompanyUser())
            ->setSspAssetStorageConditions($sspAssetStorageConditions);

        $assetStorageCollection = $this->sspAssetStorageReader
            ->getSspAssetStorageCollection($assetStorageCriteriaTransfer);

        $assetStorages = [];
        foreach ($assetStorageCollection->getSspAssetStorages() as $assetStorage) {
            $assetStorages[$assetStorage->getReferenceOrFail()] = $assetStorage;
        }

        return $assetStorages;
    }

    protected function findAssetStorageByReference(string $assetReference): ?SspAssetStorageTransfer
    {
        $sspAssetStorageConditions = (new SspAssetStorageConditionsTransfer())
            ->addReference($assetReference);

        $assetStorageCriteriaTransfer = (new SspAssetStorageCriteriaTransfer())
            ->setCompanyUser($this->companyUserClient->findCompanyUser())
            ->setSspAssetStorageConditions($sspAssetStorageConditions);

        $assetStorageCollection = $this->sspAssetStorageReader
            ->getSspAssetStorageCollection($assetStorageCriteriaTransfer);

        if ($assetStorageCollection->getSspAssetStorages()->count() === 0) {
            return null;
        }

        return $assetStorageCollection->getSspAssetStorages()->getIterator()->current();
    }

    protected function checkCompatibility(?SspAssetStorageTransfer $assetStorage, ?int $idProduct): bool
    {
        if (!$assetStorage || !$idProduct) {
            return false;
        }

        if (!$assetStorage->getSspModels()->count()) {
            return false;
        }

        $productListIds = $this->collectProductListIdsFromModels(
            $this->extractSspModelIds($assetStorage->getSspModels()),
        );

        if (!$productListIds) {
            return false;
        }

        return $this->isProductInProductLists($idProduct, $productListIds);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\SspModelTransfer> $sspModelTransfers
     *
     * @return list<int>
     */
    protected function extractSspModelIds(ArrayObject $sspModelTransfers): array
    {
        $sspModelIds = [];
        foreach ($sspModelTransfers as $sspModelTransfer) {
            $idSspModel = $sspModelTransfer->getIdSspModel();
            if ($idSspModel !== null) {
                $sspModelIds[] = $idSspModel;
            }
        }

        return $sspModelIds;
    }

    /**
     * @param list<int> $modelIds
     *
     * @return list<int>
     */
    protected function collectProductListIdsFromModels(array $modelIds): array
    {
        $modelStorageCollection = $this->getModelStorageByIds($modelIds);

        $productListIds = [];

        foreach ($modelStorageCollection->getSspModelStorages() as $modelStorage) {
            $whitelistIds = $this->extractWhitelistIds($modelStorage);
            $productListIds = array_merge($productListIds, $whitelistIds);
        }

        return $productListIds;
    }

    /**
     * @param \Generated\Shared\Transfer\SspModelStorageTransfer $modelStorageTransfer
     *
     * @return list<int>
     */
    protected function extractWhitelistIds(SspModelStorageTransfer $modelStorageTransfer): array
    {
        $whitelistIds = [];

        foreach ($modelStorageTransfer->getWhitelists() as $productListTransfer) {
            $whitelistIds[] = $productListTransfer->getIdProductListOrFail();
        }

        return $whitelistIds;
    }

    /**
     * @param list<int> $modelIds
     *
     * @return \Generated\Shared\Transfer\SspModelStorageCollectionTransfer
     */
    protected function getModelStorageByIds(array $modelIds): SspModelStorageCollectionTransfer
    {
        $modelCriteriaTransfer = (new SspModelStorageCriteriaTransfer())
            ->setSspModelStorageConditions(
                (new SspModelStorageConditionsTransfer())
                    ->setSspModelIds($modelIds),
            );

        return $this->sspModelStorageReader
            ->getSspModelStorageCollection($modelCriteriaTransfer);
    }

    /**
     * @param int $idProduct
     * @param list<int> $productListIds
     *
     * @return bool
     */
    protected function isProductInProductLists(int $idProduct, array $productListIds): bool
    {
        $productConcreteProductListStorage = $this->productListStorageClient
            ->findProductConcreteProductListStorage($idProduct);

        if (!$productConcreteProductListStorage) {
            return false;
        }

        return (bool)array_intersect($productListIds, $productConcreteProductListStorage->getIdWhitelists());
    }
}
