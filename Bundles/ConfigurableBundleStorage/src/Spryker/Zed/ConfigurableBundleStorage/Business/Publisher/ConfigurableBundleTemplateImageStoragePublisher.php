<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Business\Publisher;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateImageStorageTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ProductImageSetStorageTransfer;
use Generated\Shared\Transfer\ProductImageStorageTransfer;
use Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateImageStorage;
use Spryker\Zed\ConfigurableBundleStorage\Business\Reader\ConfigurableBundleReaderInterface;
use Spryker\Zed\ConfigurableBundleStorage\ConfigurableBundleStorageConfig;
use Spryker\Zed\ConfigurableBundleStorage\Dependency\Facade\ConfigurableBundleStorageToLocaleFacadeInterface;
use Spryker\Zed\ConfigurableBundleStorage\Dependency\Facade\ConfigurableBundleStorageToProductImageFacadeInterface;
use Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageEntityManagerInterface;
use Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageRepositoryInterface;

class ConfigurableBundleTemplateImageStoragePublisher implements ConfigurableBundleTemplateImageStoragePublisherInterface
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
     * @var \Spryker\Zed\ConfigurableBundleStorage\Dependency\Facade\ConfigurableBundleStorageToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ConfigurableBundleStorage\ConfigurableBundleStorageConfig
     */
    protected $configurableBundleStorageConfig;

    /**
     * @var \Spryker\Zed\ConfigurableBundleStorage\Dependency\Facade\ConfigurableBundleStorageToProductImageFacadeInterface
     */
    protected $productImageFacade;

    /**
     * @param \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageRepositoryInterface $configurableBundleStorageRepository
     * @param \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageEntityManagerInterface $configurableBundleStorageEntityManager
     * @param \Spryker\Zed\ConfigurableBundleStorage\Business\Reader\ConfigurableBundleReaderInterface $configurableBundleReader
     * @param \Spryker\Zed\ConfigurableBundleStorage\Dependency\Facade\ConfigurableBundleStorageToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ConfigurableBundleStorage\ConfigurableBundleStorageConfig $configurableBundleStorageConfig
     * @param \Spryker\Zed\ConfigurableBundleStorage\Dependency\Facade\ConfigurableBundleStorageToProductImageFacadeInterface $productImageFacade
     */
    public function __construct(
        ConfigurableBundleStorageRepositoryInterface $configurableBundleStorageRepository,
        ConfigurableBundleStorageEntityManagerInterface $configurableBundleStorageEntityManager,
        ConfigurableBundleReaderInterface $configurableBundleReader,
        ConfigurableBundleStorageToLocaleFacadeInterface $localeFacade,
        ConfigurableBundleStorageConfig $configurableBundleStorageConfig,
        ConfigurableBundleStorageToProductImageFacadeInterface $productImageFacade
    ) {
        $this->configurableBundleStorageRepository = $configurableBundleStorageRepository;
        $this->configurableBundleStorageEntityManager = $configurableBundleStorageEntityManager;
        $this->configurableBundleReader = $configurableBundleReader;
        $this->configurableBundleStorageConfig = $configurableBundleStorageConfig;
        $this->localeFacade = $localeFacade;
        $this->productImageFacade = $productImageFacade;
    }

    /**
     * @param int[] $configurableBundleTemplateIds
     *
     * @return void
     */
    public function publish(array $configurableBundleTemplateIds): void
    {
        $configurableBundleTemplateIds = array_unique(array_filter($configurableBundleTemplateIds));

        if (!$configurableBundleTemplateIds) {
            return;
        }

        $configurableBundleTemplateTransfers = $this->configurableBundleReader->getConfigurableBundleTemplates($configurableBundleTemplateIds);
        $localizedConfigurableBundleTemplateImageStorageEntityMap = $this->configurableBundleStorageRepository->getConfigurableBundleTemplateImageStorageEntityMap($configurableBundleTemplateIds);

        $localeTransfers = $this->localeFacade->getLocaleCollection();

        foreach ($configurableBundleTemplateTransfers as $configurableBundleTemplateTransfer) {
            if (!$configurableBundleTemplateTransfer->getIsActive() || !$configurableBundleTemplateTransfer->getProductImageSets()->count()) {
                continue;
            }

            $localizedConfigurableBundleTemplateImageStorageEntityMap = $this->saveConfigurableBundleTemplateImages(
                $configurableBundleTemplateTransfer,
                $localizedConfigurableBundleTemplateImageStorageEntityMap,
                $localeTransfers
            );
        }

        $this->sanitizeConfigurableBundleTemplateImageStorageEntities($localizedConfigurableBundleTemplateImageStorageEntityMap);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateImageStorage[][] $localizedConfigurableBundleTemplateImageStorageEntityMap
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateImageStorage[][]
     */
    protected function saveConfigurableBundleTemplateImages(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        array $localizedConfigurableBundleTemplateImageStorageEntityMap,
        array $localeTransfers
    ): array {
        $idConfigurableBundleTemplate = $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate();

        foreach ($localeTransfers as $localeName => $localeTransfer) {
            $configurableBundleTemplateImageStorageEntity = $localizedConfigurableBundleTemplateImageStorageEntityMap[$idConfigurableBundleTemplate][$localeName]
                ?? new SpyConfigurableBundleTemplateImageStorage();

            $productImageSetTransfers = $this->productImageFacade->resolveProductImageSetsForLocale(
                $configurableBundleTemplateTransfer->getProductImageSets(),
                $localeName
            );

            $this->saveConfigurableBundleTemplateImageStorageEntity(
                $localeName,
                $productImageSetTransfers->getArrayCopy(),
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
            ->setData($configurableBundleTemplateImageStorageTransfer->toArray())
            ->setIsSendingToQueue($this->configurableBundleStorageConfig->isSendingToQueue());

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
}
