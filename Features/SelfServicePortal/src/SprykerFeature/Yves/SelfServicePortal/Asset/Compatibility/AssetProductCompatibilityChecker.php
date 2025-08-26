<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Asset\Compatibility;

use ArrayObject;
use Generated\Shared\Transfer\SspAssetStorageConditionsTransfer;
use Generated\Shared\Transfer\SspAssetStorageCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetStorageTransfer;
use Generated\Shared\Transfer\SspModelStorageCollectionTransfer;
use Generated\Shared\Transfer\SspModelStorageConditionsTransfer;
use Generated\Shared\Transfer\SspModelStorageCriteriaTransfer;
use Generated\Shared\Transfer\SspModelStorageTransfer;
use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Spryker\Client\ProductListStorage\ProductListStorageClientInterface;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface;

class AssetProductCompatibilityChecker implements AssetProductCompatibilityCheckerInterface
{
    public function __construct(
        protected SelfServicePortalClientInterface $selfServicePortalClient,
        protected ProductListStorageClientInterface $productListStorageClient,
        protected CompanyUserClientInterface $companyUserClient
    ) {
    }

    public function isAssetCompatibleToProduct(string $assetReference, int $idProduct): bool
    {
        if (!$assetReference) {
            return false;
        }

        $sspAssetStorageTransfer = $this->findAssetStorageByReference($assetReference);
        if (!$sspAssetStorageTransfer) {
            return false;
        }

        if (!$sspAssetStorageTransfer->getSspModels()->count()) {
            return false;
        }

        $productListIds = $this->collectProductListIdsFromModels(
            $this->extractSspModelIds($sspAssetStorageTransfer->getSspModels()),
        );

        if (!$productListIds) {
            return false;
        }

        return $this->isProductInProductLists($idProduct, $productListIds);
    }

    protected function findAssetStorageByReference(string $assetReference): ?SspAssetStorageTransfer
    {
        $sspAssetStorageConditions = (new SspAssetStorageConditionsTransfer())
            ->addReference($assetReference);

        $assetStorageCriteriaTransfer = (new SspAssetStorageCriteriaTransfer())
            ->setCompanyUser($this->companyUserClient->findCompanyUser())
            ->setSspAssetStorageConditions($sspAssetStorageConditions);

        $assetStorageCollection = $this->selfServicePortalClient
            ->getSspAssetStorageCollection($assetStorageCriteriaTransfer);

        if ($assetStorageCollection->getSspAssetStorages()->count() === 0) {
            return null;
        }

        return $assetStorageCollection->getSspAssetStorages()->getIterator()->current();
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

        return $this->selfServicePortalClient
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
