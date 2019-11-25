<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Business\Publisher;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateImageStorageTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ProductImageSetStorageTransfer;
use Generated\Shared\Transfer\ProductImageStorageTransfer;
use Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateImageStorage;
use Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorage;
use Spryker\Zed\ConfigurableBundleStorage\Business\Reader\ConfigurableBundleReaderInterface;
use Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageEntityManagerInterface;
use Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageRepositoryInterface;

class ConfigurableBundleStoragePublisher implements ConfigurableBundleStoragePublisherInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageRepositoryInterface
     */
    protected $configurableBundleStorageRepository;

    /**
     * @var \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageEntityManagerInterface
     */
    protected $configurableBundleStorageEntityManager;

    /**
     * @var \Spryker\Zed\ConfigurableBundleStorage\Business\Reader\ConfigurableBundleReaderInterface
     */
    protected $configurableBundleReader;

    /**
     * @param \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageRepositoryInterface $configurableBundleStorageRepository
     * @param \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageEntityManagerInterface $configurableBundleStorageEntityManager
     * @param \Spryker\Zed\ConfigurableBundleStorage\Business\Reader\ConfigurableBundleReaderInterface $configurableBundleReader
     */
    public function __construct(
        ConfigurableBundleStorageRepositoryInterface $configurableBundleStorageRepository,
        ConfigurableBundleStorageEntityManagerInterface $configurableBundleStorageEntityManager,
        ConfigurableBundleReaderInterface $configurableBundleReader
    ) {
        $this->configurableBundleStorageRepository = $configurableBundleStorageRepository;
        $this->configurableBundleStorageEntityManager = $configurableBundleStorageEntityManager;
        $this->configurableBundleReader = $configurableBundleReader;
    }

    /**
     * @param int[] $configurableBundleTemplateIds
     *
     * @return void
     */
    public function publishConfigurableBundleTemplates(array $configurableBundleTemplateIds): void
    {
        $configurableBundleTemplateTransfers = $this->configurableBundleReader->getConfigurableBundleTemplates($configurableBundleTemplateIds);
        $configurableBundleTemplateStorageEntityMap = $this->configurableBundleStorageRepository->getConfigurableBundleTemplateStorageEntityMap($configurableBundleTemplateIds);

        foreach ($configurableBundleTemplateTransfers as $configurableBundleTemplateTransfer) {
            $idConfigurableBundleTemplate = $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate();
            $configurableBundleTemplateStorageEntity = $configurableBundleTemplateStorageEntityMap[$idConfigurableBundleTemplate]
                ?? new SpyConfigurableBundleTemplateStorage();

            if (isset($configurableBundleTemplateStorageEntityMap[$idConfigurableBundleTemplate]) && !$configurableBundleTemplateTransfer->getIsActive()) {
                $this->configurableBundleStorageEntityManager->deleteConfigurableBundleTemplateStorageEntity($configurableBundleTemplateStorageEntity);

                continue;
            }

            $configurableBundleTemplateStorageEntity = $this->mapConfigurableBundleTemplateTransferToConfigurableBundleTemplateStorageEntity(
                $configurableBundleTemplateTransfer,
                $configurableBundleTemplateStorageEntity
            );

            $this->configurableBundleStorageEntityManager->saveConfigurableBundleTemplateStorageEntity($configurableBundleTemplateStorageEntity);
        }
    }

    /**
     * @param int[] $configurableBundleTemplateIds
     *
     * @return void
     */
    public function publishConfigurableBundleTemplateImages(array $configurableBundleTemplateIds): void
    {
        $configurableBundleTemplateTransfers = $this->configurableBundleReader->getConfigurableBundleTemplates($configurableBundleTemplateIds);
        $localizedConfigurableBundleTemplateImageStorageEntityMap = $this->configurableBundleStorageRepository->getConfigurableBundleTemplateImageStorageEntityMap($configurableBundleTemplateIds);

        foreach ($configurableBundleTemplateTransfers as $configurableBundleTemplateTransfer) {
            if (!$configurableBundleTemplateTransfer->getIsActive()) {
                continue;
            }

            $localizedConfigurableBundleTemplateImageStorageEntityMap = $this->saveConfigurableBundleTemplateImages(
                $configurableBundleTemplateTransfer,
                $localizedConfigurableBundleTemplateImageStorageEntityMap
            );
        }

        $this->sanitizeConfigurableBundleTemplateImageStorageEntities($localizedConfigurableBundleTemplateImageStorageEntityMap);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorage $configurableBundleTemplateStorageEntity
     *
     * @return \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorage
     */
    protected function mapConfigurableBundleTemplateTransferToConfigurableBundleTemplateStorageEntity(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        SpyConfigurableBundleTemplateStorage $configurableBundleTemplateStorageEntity
    ): SpyConfigurableBundleTemplateStorage {
        $configurableBundleTemplateSlotStorageTransfers = [];
        $configurableBundleTemplateSlotTransfers = $this->configurableBundleReader->getConfigurableBundleTemplateSlots($configurableBundleTemplateTransfer);

        foreach ($configurableBundleTemplateSlotTransfers as $configurableBundleTemplateSlotTransfer) {
            $configurableBundleTemplateSlotStorageTransfers[] = (new ConfigurableBundleTemplateSlotStorageTransfer())
                ->setIdConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot())
                ->setUuid($configurableBundleTemplateSlotTransfer->getUuid())
                ->setName($configurableBundleTemplateSlotTransfer->getName())
                ->setIdProductList($configurableBundleTemplateSlotTransfer->getProductList()->getIdProductList());
        }

        $configurableBundleTemplateStorageTransfer = (new ConfigurableBundleTemplateStorageTransfer())
            ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            ->setName($configurableBundleTemplateTransfer->getName())
            ->setUuid($configurableBundleTemplateTransfer->getUuid())
            ->setSlots(new ArrayObject($configurableBundleTemplateSlotStorageTransfers));

        $configurableBundleTemplateStorageEntity
            ->setFkConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            ->setData($configurableBundleTemplateStorageTransfer->toArray());

        return $configurableBundleTemplateStorageEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateImageStorage[][] $localizedConfigurableBundleTemplateImageStorageEntityMap
     *
     * @return \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateImageStorage[][]
     */
    protected function saveConfigurableBundleTemplateImages(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        array $localizedConfigurableBundleTemplateImageStorageEntityMap
    ): array {
        $idConfigurableBundleTemplate = $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate();
        $localizedProductImageSetTransfers = $this->mapProductImageSetsByLocaleName($configurableBundleTemplateTransfer->getProductImageSets());

        foreach ($localizedProductImageSetTransfers as $localeName => $productImageSetTransfers) {
            $configurableBundleTemplateImageStorageEntity = $localizedConfigurableBundleTemplateImageStorageEntityMap[$idConfigurableBundleTemplate][$localeName]
                ?? new SpyConfigurableBundleTemplateImageStorage();

            $this->saveConfigurableBundleTemplateImageStorageEntity(
                $localeName,
                $productImageSetTransfers,
                $configurableBundleTemplateTransfer,
                $configurableBundleTemplateImageStorageEntity
            );

            if (!$configurableBundleTemplateImageStorageEntity->isNew()) {
                unset($localizedConfigurableBundleTemplateImageStorageEntityMap[$idConfigurableBundleTemplate][$localeName]);
            }
        }

        return $localizedConfigurableBundleTemplateImageStorageEntityMap;
    }

    /**
     * @param string $localeName
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer[] $productImageSetTransfers
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateImageStorage $configurableBundleTemplateImageStorageEntity
     *
     * @return void
     */
    protected function saveConfigurableBundleTemplateImageStorageEntity(
        string $localeName,
        array $productImageSetTransfers,
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        SpyConfigurableBundleTemplateImageStorage $configurableBundleTemplateImageStorageEntity
    ): void {
        $productImageSetStorageTransfers = $this->mapProductImageSetTransfersToProductImageSetStorageTransfers($productImageSetTransfers);

        $configurableBundleTemplateImageStorageTransfer = (new ConfigurableBundleTemplateImageStorageTransfer())
            ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            ->setImageSets(new ArrayObject($productImageSetStorageTransfers));

        $configurableBundleTemplateImageStorageEntity
            ->setLocale($localeName)
            ->setFkConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            ->setData($configurableBundleTemplateImageStorageTransfer->toArray());

        $this->configurableBundleStorageEntityManager->saveConfigurableBundleTemplateImageStorageEntity($configurableBundleTemplateImageStorageEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer[] $productImageSetTransfers
     *
     * @return \Generated\Shared\Transfer\ProductImageSetStorageTransfer[]
     */
    protected function mapProductImageSetTransfersToProductImageSetStorageTransfers(array $productImageSetTransfers): array
    {
        $productImageSetStorageTransfers = [];

        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $productImageStorageTransfers = $this->mapProductImageTransfersToProductImageStorageTransfers($productImageSetTransfer->getProductImages());

            $productImageSetStorageTransfers[] = (new ProductImageSetStorageTransfer())
                ->setName($productImageSetTransfer->getName())
                ->setImages(new ArrayObject($productImageStorageTransfers));
        }

        return $productImageSetStorageTransfers;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductImageTransfer[] $productImageTransfers
     *
     * @return \Generated\Shared\Transfer\ProductImageStorageTransfer[]
     */
    protected function mapProductImageTransfersToProductImageStorageTransfers(ArrayObject $productImageTransfers): array
    {
        $productImageStorageTransfers = [];

        foreach ($productImageTransfers as $productImageTransfer) {
            $productImageStorageTransfers[] = (new ProductImageStorageTransfer())
                ->setIdProductImage($productImageTransfer->getIdProductImage())
                ->setExternalUrlLarge($productImageTransfer->getExternalUrlLarge())
                ->setExternalUrlSmall($productImageTransfer->getExternalUrlSmall());
        }

        return $productImageStorageTransfers;
    }

    /**
     * @param \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateImageStorage[][] $localizedConfigurableBundleTemplateImageStorageEntityMap
     *
     * @return void
     */
    protected function sanitizeConfigurableBundleTemplateImageStorageEntities(array $localizedConfigurableBundleTemplateImageStorageEntityMap): void
    {
        foreach ($localizedConfigurableBundleTemplateImageStorageEntityMap as $configurableBundleTemplateImageStorageEntities) {
            foreach ($configurableBundleTemplateImageStorageEntities as $configurableBundleTemplateImageStorageEntity) {
                $this->configurableBundleStorageEntityManager->deleteConfigurableBundleTemplateImageStorageEntity($configurableBundleTemplateImageStorageEntity);
            }
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductImageSetTransfer[] $productImageSetTransfers
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[][]
     */
    protected function mapProductImageSetsByLocaleName(ArrayObject $productImageSetTransfers): array
    {
        $localizedProductImageSetTransfers = [];

        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $localizedProductImageSetTransfers[$productImageSetTransfer->getLocale()->getLocaleName()][] = $productImageSetTransfer;
        }

        return $localizedProductImageSetTransfers;
    }
}
