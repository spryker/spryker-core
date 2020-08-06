<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Business\Unpublisher;

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
     * @param int[] $configurableBundleTemplateIds
     *
     * @return void
     */
    public function unpublish(array $configurableBundleTemplateIds): void
    {
        $configurableBundleTemplateIds = array_unique(array_filter($configurableBundleTemplateIds));

        if (!$configurableBundleTemplateIds) {
            return;
        }

        $configurableBundleTemplateImageStorageEntityMap = $this->configurableBundleStorageRepository->getConfigurableBundleTemplateImageStorageEntityMap($configurableBundleTemplateIds);

        foreach ($configurableBundleTemplateImageStorageEntityMap as $configurableBundleTemplateImageStorageEntities) {
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
}
