<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Business\Publisher;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate;
use Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorage;
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
     * @param \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageRepositoryInterface $configurableBundleStorageRepository
     * @param \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageEntityManagerInterface $configurableBundleStorageEntityManager
     */
    public function __construct(
        ConfigurableBundleStorageRepositoryInterface $configurableBundleStorageRepository,
        ConfigurableBundleStorageEntityManagerInterface $configurableBundleStorageEntityManager
    ) {
        $this->configurableBundleStorageRepository = $configurableBundleStorageRepository;
        $this->configurableBundleStorageEntityManager = $configurableBundleStorageEntityManager;
    }

    /**
     * @param int[] $configurableBundleIds
     *
     * @return void
     */
    public function publishConfigurableBundleTemplates(array $configurableBundleIds): void
    {
        $configurableBundleTemplateEntityMap = $this->configurableBundleStorageRepository->getConfigurableBundleTemplateEntityMap($configurableBundleIds);
        $configurableBundleTemplateStorageEntityMap = $this->configurableBundleStorageRepository->getConfigurableBundleTemplateStorageEntityMap($configurableBundleIds);

        foreach ($configurableBundleTemplateEntityMap as $configurableBundleTemplateEntity) {
            $idConfigurableBundleTemplate = $configurableBundleTemplateEntity->getIdConfigurableBundleTemplate();
            $configurableBundleTemplateStorageEntity = $configurableBundleTemplateStorageEntityMap[$idConfigurableBundleTemplate]
                ?? new SpyConfigurableBundleTemplateStorage();

            $configurableBundleTemplateStorageEntity = $this->mapConfigurableBundleTemplateEntityToConfigurableBundleTemplateStorageEntity(
                $configurableBundleTemplateEntity,
                $configurableBundleTemplateStorageEntity
            );

            $this->saveStorageEntity($configurableBundleTemplateStorageEntity);
        }
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate $configurableBundleTemplateEntity
     * @param \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorage $configurableBundleTemplateStorageEntity
     *
     * @return \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorage
     */
    public function mapConfigurableBundleTemplateEntityToConfigurableBundleTemplateStorageEntity(
        SpyConfigurableBundleTemplate $configurableBundleTemplateEntity,
        SpyConfigurableBundleTemplateStorage $configurableBundleTemplateStorageEntity
    ): SpyConfigurableBundleTemplateStorage {
        $configurableBundleTemplateSlotStorageTransfers = [];

        foreach ($configurableBundleTemplateEntity->getSpyConfigurableBundleTemplateSlots() as $configurableBundleTemplateSlotEntity) {
            $configurableBundleTemplateSlotStorageTransfers[] = (new ConfigurableBundleTemplateSlotStorageTransfer())
                ->setUuid($configurableBundleTemplateSlotEntity->getUuid())
                ->setProductListId($configurableBundleTemplateSlotEntity->getFkProductList());
        }

        $configurableBundleTemplateStorageTransfer = (new ConfigurableBundleTemplateStorageTransfer())
            ->setName($configurableBundleTemplateEntity->getName())
            ->setUuid($configurableBundleTemplateEntity->getUuid())
            ->setSlots(new ArrayObject($configurableBundleTemplateSlotStorageTransfers));

        $configurableBundleTemplateStorageEntity->setFkConfigurableBundleTemplate($configurableBundleTemplateEntity->getIdConfigurableBundleTemplate())
            ->setData($configurableBundleTemplateStorageTransfer->toArray());

        return $configurableBundleTemplateStorageEntity;
    }

    /**
     * @param \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorage $configurableBundleTemplateStorageEntity
     *
     * @return void
     */
    protected function saveStorageEntity(SpyConfigurableBundleTemplateStorage $configurableBundleTemplateStorageEntity): void
    {
        $this->configurableBundleStorageEntityManager->saveConfigurableBundleTemplateStorageEntity($configurableBundleTemplateStorageEntity);
    }
}
