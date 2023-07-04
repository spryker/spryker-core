<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Business\Writer;

use Generated\Shared\Transfer\IterableProductOfferServicesConditionsTransfer;
use Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferServiceStorageTransfer;
use Generated\Shared\Transfer\ProductOfferServicesTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductOfferServicePointStorage\Business\Mapper\ProductOfferServiceMapperInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Business\Reader\ProductOfferReaderInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToStoreFacadeInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Persistence\ProductOfferServicePointStorageEntityManagerInterface;

class ProductOfferServiceStorageWriter implements ProductOfferServiceStorageWriterInterface
{
    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const PRODUCT_OFFER_APPROVAL_STATUS_APPROVED = 'approved';

    /**
     * @var \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface
     */
    protected ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface $productOfferServicePointFacade;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToStoreFacadeInterface
     */
    protected ProductOfferServicePointStorageToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointStorage\Persistence\ProductOfferServicePointStorageEntityManagerInterface
     */
    protected ProductOfferServicePointStorageEntityManagerInterface $productOfferServicePointStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointStorage\Business\Mapper\ProductOfferServiceMapperInterface
     */
    protected ProductOfferServiceMapperInterface $productOfferServiceMapper;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointStorage\Business\Reader\ProductOfferReaderInterface
     */
    protected ProductOfferReaderInterface $productOfferReader;

    /**
     * @var list<\Spryker\Zed\ProductOfferServicePointStorageExtension\Dependeency\Plugin\ProductOfferServiceStorageFilterPluginInterface>
     */
    protected array $productOfferServiceStorageFilterPlugins;

    /**
     * @param \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface $productOfferServicePointFacade
     * @param \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductOfferServicePointStorage\Persistence\ProductOfferServicePointStorageEntityManagerInterface $productOfferServicePointStorageEntityManager
     * @param \Spryker\Zed\ProductOfferServicePointStorage\Business\Mapper\ProductOfferServiceMapperInterface $productOfferServiceMapper
     * @param \Spryker\Zed\ProductOfferServicePointStorage\Business\Reader\ProductOfferReaderInterface $productOfferReader
     * @param list<\Spryker\Zed\ProductOfferServicePointStorageExtension\Dependeency\Plugin\ProductOfferServiceStorageFilterPluginInterface> $productOfferServiceStorageFilterPlugins
     */
    public function __construct(
        ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface $productOfferServicePointFacade,
        ProductOfferServicePointStorageToStoreFacadeInterface $storeFacade,
        ProductOfferServicePointStorageEntityManagerInterface $productOfferServicePointStorageEntityManager,
        ProductOfferServiceMapperInterface $productOfferServiceMapper,
        ProductOfferReaderInterface $productOfferReader,
        array $productOfferServiceStorageFilterPlugins
    ) {
        $this->productOfferServicePointFacade = $productOfferServicePointFacade;
        $this->storeFacade = $storeFacade;
        $this->productOfferServicePointStorageEntityManager = $productOfferServicePointStorageEntityManager;
        $this->productOfferServiceMapper = $productOfferServiceMapper;
        $this->productOfferReader = $productOfferReader;
        $this->productOfferServiceStorageFilterPlugins = $productOfferServiceStorageFilterPlugins;
    }

    /**
     * @param list<int> $productOfferIds
     *
     * @return void
     */
    public function writeProductOfferServiceStorageCollection(array $productOfferIds): void
    {
        /** @var list<int> $productOfferIds */
        $productOfferIds = array_filter($productOfferIds);
        if (!$productOfferIds) {
            return;
        }

        $iterableProductOfferServicesConditionsTransfer = (new IterableProductOfferServicesConditionsTransfer())
            ->setIsActiveProductOffer(true)
            ->setProductOfferApprovalStatuses([static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED])
            ->setIsActiveConcreteProduct(true)
            ->setIsActiveService(true)
            ->setIsActiveServicePoint(true)
            ->setWithServicePointRelations(true)
            ->setProductOfferIds($productOfferIds);

        $iterableProductOfferServicesCriteriaTransfer = (new IterableProductOfferServicesCriteriaTransfer())
            ->setIterableProductOfferServicesConditions($iterableProductOfferServicesConditionsTransfer);

        $storeTransfers = $this->storeFacade->getAllStores();

        $productOfferServicesIterator = $this->productOfferServicePointFacade
            ->iterateProductOfferServices($iterableProductOfferServicesCriteriaTransfer);

        $processedProductOfferIds = [];
        foreach ($productOfferServicesIterator as $productOfferServicesTransfers) {
            $productOfferServicesTransfers = $this->executeProductOfferServiceStorageFilterPlugins($productOfferServicesTransfers);

            $processedProductOfferIds += $this->writeCollectionPerStore($productOfferServicesTransfers, $storeTransfers);
        }

        /** @var list<int> $productOfferIdsToDelete */
        $productOfferIdsToDelete = array_diff($productOfferIds, $processedProductOfferIds);

        $productOfferReferencesToDelete = $this->productOfferReader->getProductOfferReferencesByProductOfferIds($productOfferIdsToDelete);
        if (!$productOfferReferencesToDelete) {
            return;
        }

        $this->productOfferServicePointStorageEntityManager->deleteProductOfferServiceStorageByProductOfferReferences($productOfferReferencesToDelete);
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferServicesTransfer> $productOfferServicesTransfers
     * @param list<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return list<int>
     */
    protected function writeCollectionPerStore(
        array $productOfferServicesTransfers,
        array $storeTransfers
    ): array {
        $processedProductOfferIds = [];
        foreach ($productOfferServicesTransfers as $productOfferServicesTransfer) {
            $productOfferReference = $productOfferServicesTransfer->getProductOfferOrFail()->getProductOfferReferenceOrFail();
            $processedProductOfferIds[] = $productOfferServicesTransfer->getProductOfferOrFail()->getIdProductOfferOrFail();

            foreach ($storeTransfers as $storeTransfer) {
                if (!$this->isProductOfferServicesAvailableInStore($productOfferServicesTransfer, $storeTransfer)) {
                    $this->productOfferServicePointStorageEntityManager->deleteProductOfferServiceStorageByProductOfferReferences(
                        [$productOfferReference],
                        $storeTransfer->getNameOrFail(),
                    );

                    continue;
                }

                $productOfferServiceStorageTransfer = $this->productOfferServiceMapper->mapProductOfferServicesTransferToProductOfferServiceStorageTransfer(
                    $productOfferServicesTransfer,
                    new ProductOfferServiceStorageTransfer(),
                );

                $this->productOfferServicePointStorageEntityManager->saveProductOfferServiceForStore($productOfferServiceStorageTransfer, $storeTransfer->getNameOrFail());
            }
        }

        return $processedProductOfferIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicesTransfer $productOfferServicesTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isProductOfferServicesAvailableInStore(ProductOfferServicesTransfer $productOfferServicesTransfer, StoreTransfer $storeTransfer): bool
    {
        if (!$this->isProductOfferAvailableInStore($productOfferServicesTransfer->getProductOfferOrFail(), $storeTransfer)) {
            return false;
        }

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer> $serviceTransfers */
        $serviceTransfers = $productOfferServicesTransfer->getServices();

        return $this->isServiceAvailableInStore($serviceTransfers->getIterator()->current(), $storeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isProductOfferAvailableInStore(ProductOfferTransfer $productOfferTransfer, StoreTransfer $storeTransfer): bool
    {
        foreach ($productOfferTransfer->getStores() as $productOfferStoreTransfer) {
            if ($productOfferStoreTransfer->getIdStoreOrFail() === $storeTransfer->getIdStoreOrFail()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isServiceAvailableInStore(ServiceTransfer $serviceTransfer, StoreTransfer $storeTransfer): bool
    {
        if (!$serviceTransfer->getServicePointOrFail()->getStoreRelation()) {
            return false;
        }

        foreach ($serviceTransfer->getServicePointOrFail()->getStoreRelationOrFail()->getStores() as $servicePointStoreTransfer) {
            if ($servicePointStoreTransfer->getIdStoreOrFail() === $storeTransfer->getIdStoreOrFail()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferServicesTransfer> $productOfferServicesTransfers
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferServicesTransfer>
     */
    protected function executeProductOfferServiceStorageFilterPlugins(array $productOfferServicesTransfers): array
    {
        foreach ($this->productOfferServiceStorageFilterPlugins as $productOfferServiceStorageFilterPlugin) {
            $productOfferServicesTransfers = $productOfferServiceStorageFilterPlugin->filterProductOfferServices($productOfferServicesTransfers);
        }

        return $productOfferServicesTransfers;
    }
}
