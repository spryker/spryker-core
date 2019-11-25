<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Business\Unpublisher;

use Spryker\Zed\ConfigurableBundleStorage\Business\Reader\ConfigurableBundleReaderInterface;
use Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageEntityManagerInterface;
use Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageRepositoryInterface;

class ConfigurableBundleTemplateImageStorageUnpublisher implements ConfigurableBundleTemplateImageStorageUnpublisherInterface
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
    public function unpublish(array $configurableBundleTemplateIds): void
    {
        $configurableBundleTemplateImageStorageEntityMap = $this->configurableBundleStorageRepository->getConfigurableBundleTemplateImageStorageEntityMap($configurableBundleTemplateIds);

        $configurableBundleTemplateTransfers = $this->configurableBundleReader->getConfigurableBundleTemplates($configurableBundleTemplateIds);
        $configurableBundleTemplateIds = $this->extractConfigurableBundleTemplateIds($configurableBundleTemplateTransfers);

        foreach ($configurableBundleTemplateImageStorageEntityMap as $idConfigurableBundleTemplate => $configurableBundleTemplateImageStorageEntities) {
            if (!in_array($idConfigurableBundleTemplate, $configurableBundleTemplateIds, true)) {
                continue;
            }

            $this->deleteConfigurableBundleTemplateImageStorageEntities($configurableBundleTemplateImageStorageEntities);
        }
    }

    /**
     * @param \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateImageStorage[] $configurableBundleTemplateImageStorageEntities
     *
     * @return void
     */
    protected function deleteConfigurableBundleTemplateImageStorageEntities(array $configurableBundleTemplateImageStorageEntities): void
    {
        foreach ($configurableBundleTemplateImageStorageEntities as $configurableBundleTemplateImageStorageEntity) {
            $this->configurableBundleStorageEntityManager->deleteConfigurableBundleTemplateImageStorageEntity($configurableBundleTemplateImageStorageEntity);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer[] $configurableBundleTemplateTransfers
     *
     * @return int[]
     */
    protected function extractConfigurableBundleTemplateIds(array $configurableBundleTemplateTransfers): array
    {
        $configurableBundleTemplateIds = [];

        foreach ($configurableBundleTemplateTransfers as $configurableBundleTemplateTransfer) {
            $configurableBundleTemplateIds[] = $configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate();
        }

        return $configurableBundleTemplateIds;
    }
}
