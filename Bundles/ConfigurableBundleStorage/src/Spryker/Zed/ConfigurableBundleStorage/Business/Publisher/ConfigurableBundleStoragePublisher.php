<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Business\Publisher;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorage;
use Spryker\Zed\ConfigurableBundleStorage\Business\Reader\ConfigurableBundleReaderInterface;
use Spryker\Zed\ConfigurableBundleStorage\ConfigurableBundleStorageConfig;
use Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageEntityManagerInterface;
use Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageRepositoryInterface;

class ConfigurableBundleStoragePublisher implements ConfigurableBundleStoragePublisherInterface
{
    protected const KEY_IMAGE_SET = 'image_sets';

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
     * @var \Spryker\Zed\ConfigurableBundleStorage\ConfigurableBundleStorageConfig
     */
    protected $configurableBundleStorageConfig;

    /**
     * @param \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageRepositoryInterface $configurableBundleStorageRepository
     * @param \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageEntityManagerInterface $configurableBundleStorageEntityManager
     * @param \Spryker\Zed\ConfigurableBundleStorage\Business\Reader\ConfigurableBundleReaderInterface $configurableBundleReader
     * @param \Spryker\Zed\ConfigurableBundleStorage\ConfigurableBundleStorageConfig $configurableBundleStorageConfig
     */
    public function __construct(
        ConfigurableBundleStorageRepositoryInterface $configurableBundleStorageRepository,
        ConfigurableBundleStorageEntityManagerInterface $configurableBundleStorageEntityManager,
        ConfigurableBundleReaderInterface $configurableBundleReader,
        ConfigurableBundleStorageConfig $configurableBundleStorageConfig
    ) {
        $this->configurableBundleStorageRepository = $configurableBundleStorageRepository;
        $this->configurableBundleStorageEntityManager = $configurableBundleStorageEntityManager;
        $this->configurableBundleReader = $configurableBundleReader;
        $this->configurableBundleStorageConfig = $configurableBundleStorageConfig;
    }

    /**
     * @param int[] $configurableBundleTemplateIds
     *
     * @return void
     */
    public function publishConfigurableBundleTemplates(array $configurableBundleTemplateIds): void
    {
        $configurableBundleTemplateIds = array_unique(array_filter($configurableBundleTemplateIds));

        if (!$configurableBundleTemplateIds) {
            return;
        }

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

            if (!$configurableBundleTemplateTransfer->getIsActive()) {
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

        $configurableBundleTemplateStorageData = $configurableBundleTemplateStorageTransfer->toArray();
        unset($configurableBundleTemplateStorageData[static::KEY_IMAGE_SET]);

        $configurableBundleTemplateStorageEntity
            ->setFkConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate())
            ->setData($configurableBundleTemplateStorageData)
            ->setIsSendingToQueue($this->configurableBundleStorageConfig->isSendingToQueue());

        return $configurableBundleTemplateStorageEntity;
    }
}
