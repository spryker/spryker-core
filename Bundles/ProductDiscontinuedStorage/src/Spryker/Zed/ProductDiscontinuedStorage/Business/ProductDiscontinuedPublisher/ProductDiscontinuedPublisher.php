<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedPublisher;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage;
use Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToLocaleFacadeInterface;
use Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToProductDiscontinuedFacadeInterface;
use Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageEntityManagerInterface;
use Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepositoryInterface;

class ProductDiscontinuedPublisher implements ProductDiscontinuedPublisherInterface
{
    /**
     * @var \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageEntityManagerInterface
     */
    protected $productDiscontinuedStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepositoryInterface
     */
    protected $productDiscontinuedStorageRepository;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToProductDiscontinuedFacadeInterface
     */
    protected $productDiscontinuedFacade;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageEntityManagerInterface $productDiscontinuedStorageEntityManager
     * @param \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepositoryInterface $productDiscontinuedStorageRepository
     * @param \Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToProductDiscontinuedFacadeInterface $productDiscontinuedFacade
     * @param \Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductDiscontinuedStorageEntityManagerInterface $productDiscontinuedStorageEntityManager,
        ProductDiscontinuedStorageRepositoryInterface $productDiscontinuedStorageRepository,
        ProductDiscontinuedStorageToProductDiscontinuedFacadeInterface $productDiscontinuedFacade,
        ProductDiscontinuedStorageToLocaleFacadeInterface $localeFacade
    ) {
        $this->productDiscontinuedStorageEntityManager = $productDiscontinuedStorageEntityManager;
        $this->productDiscontinuedStorageRepository = $productDiscontinuedStorageRepository;
        $this->productDiscontinuedFacade = $productDiscontinuedFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int[] $productDiscontinuedIds
     *
     * @return void
     */
    public function publish(array $productDiscontinuedIds): void
    {
        $productDiscontinuedCollectionTransfer = $this->findProductDiscontinuedCollection($productDiscontinuedIds);
        $productDiscontinuedStorageEntities = $this->findProductDiscontinuedStorageEntitiesByIds($productDiscontinuedIds);

        $this->storeData($productDiscontinuedCollectionTransfer, $productDiscontinuedStorageEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
     * @param \Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage[] $productDiscontinuedStorageEntities
     *
     * @return void
     */
    protected function storeData(
        ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer,
        array $productDiscontinuedStorageEntities
    ): void {
        $indexProductDiscontinuedStorageEntities = $this->indexProductDiscontinuedStorageEntities($productDiscontinuedStorageEntities);
        $localeTransfers = $this->localeFacade->getLocaleCollection();
        foreach ($productDiscontinuedCollectionTransfer->getDiscontinuedProducts() as $productDiscontinuedTransfer) {
            $this->storeLocalizedData(
                $productDiscontinuedTransfer,
                $indexProductDiscontinuedStorageEntities,
                $localeTransfers
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     * @param \Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage[] $indexProductDiscontinuedStorageEntities
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return void
     */
    protected function storeLocalizedData(
        ProductDiscontinuedTransfer $productDiscontinuedTransfer,
        array $indexProductDiscontinuedStorageEntities,
        array $localeTransfers
    ): void {
        foreach ($localeTransfers as $localeName => $localeTransfer) {
            if (isset($indexProductDiscontinuedStorageEntities[$productDiscontinuedTransfer->getIdProductDiscontinued()][$localeName])) {
                $this->storeDataSet(
                    $productDiscontinuedTransfer,
                    $localeTransfer,
                    $indexProductDiscontinuedStorageEntities[$productDiscontinuedTransfer->getIdProductDiscontinued()][$localeName]
                );

                continue;
            }

            $this->storeDataSet($productDiscontinuedTransfer, $localeTransfer, new SpyProductDiscontinuedStorage());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage $productDiscontinuedStorage
     *
     * @return void
     */
    protected function storeDataSet(
        ProductDiscontinuedTransfer $productDiscontinuedTransfer,
        LocaleTransfer $localeTransfer,
        SpyProductDiscontinuedStorage $productDiscontinuedStorage
    ): void {
        $productDiscontinuedStorage->setFkProductDiscontinued($productDiscontinuedTransfer->getIdProductDiscontinued())
            ->setSku($productDiscontinuedTransfer->getSku())
            ->setLocale($localeTransfer->getLocaleName())
            ->setData(
                $this->mapToProductDiscontinuedStorageTransfer($productDiscontinuedTransfer, $localeTransfer)->toArray()
            );

        $this->productDiscontinuedStorageEntityManager->saveProductDiscontinuedStorageEntity($productDiscontinuedStorage);
    }

    /**
     * @param int[] $productDiscontinuedIds
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    protected function findProductDiscontinuedCollection(array $productDiscontinuedIds): ProductDiscontinuedCollectionTransfer
    {
        $criteriaFilterTransfer = (new ProductDiscontinuedCriteriaFilterTransfer)
            ->setIds($productDiscontinuedIds);

        return $this->productDiscontinuedFacade->findProductDiscontinuedCollection($criteriaFilterTransfer);
    }

    /**
     * @param int[] $productDiscontinuedIds
     *
     * @return \Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage[]
     */
    protected function findProductDiscontinuedStorageEntitiesByIds(array $productDiscontinuedIds): array
    {
        return $this->productDiscontinuedStorageRepository->findProductDiscontinuedStorageEntitiesByIds($productDiscontinuedIds);
    }

    /**
     * @param \Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage[] $productDiscontinuedStorageEntities
     *
     * @return \Orm\Zed\ProductDiscontinuedStorage\Persistence\SpyProductDiscontinuedStorage[]
     */
    protected function indexProductDiscontinuedStorageEntities(array $productDiscontinuedStorageEntities): array
    {
        $indexProductDiscontinuedStorageEntities = [];
        foreach ($productDiscontinuedStorageEntities as $discontinuedStorageEntity) {
            $indexProductDiscontinuedStorageEntities[$discontinuedStorageEntity->getFkProductDiscontinued()][$discontinuedStorageEntity->getLocale()]
                = $discontinuedStorageEntity;
        }

        return $indexProductDiscontinuedStorageEntities;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer
     */
    protected function mapToProductDiscontinuedStorageTransfer(
        ProductDiscontinuedTransfer $productDiscontinuedTransfer,
        LocaleTransfer $localeTransfer
    ): ProductDiscontinuedStorageTransfer {
        return (new ProductDiscontinuedStorageTransfer())
            ->fromArray($productDiscontinuedTransfer->toArray(), true)
            ->setNote($this->getLocalizedNote($productDiscontinuedTransfer, $localeTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function getLocalizedNote(
        ProductDiscontinuedTransfer $productDiscontinuedTransfer,
        LocaleTransfer $localeTransfer
    ): string {
        foreach ($productDiscontinuedTransfer->getProductDiscontinuedNotes() as $discontinuedNoteTransfer) {
            if ($discontinuedNoteTransfer->getFkLocale() === $localeTransfer->getIdLocale()) {
                return $discontinuedNoteTransfer->getNote();
            }
        }

        return '';
    }
}
