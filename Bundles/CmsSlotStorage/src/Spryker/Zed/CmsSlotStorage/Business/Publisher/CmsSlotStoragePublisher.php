<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Business\Publisher;

use Generated\Shared\Transfer\CmsSlotStorageTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;
use Spryker\Zed\CmsSlotStorage\Business\Mapper\CmsSlotStorageMapperInterface;
use Spryker\Zed\CmsSlotStorage\Dependency\Facade\CmsSlotStorageToCmsSlotFacadeInterface;
use Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStorageEntityManagerInterface;
use Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStorageRepositoryInterface;

class CmsSlotStoragePublisher implements CmsSlotStoragePublisherInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotStorage\Dependency\Facade\CmsSlotStorageToCmsSlotFacadeInterface
     */
    protected $cmsSlotFacade;

    /**
     * @var \Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStorageRepositoryInterface
     */
    protected $cmsSlotStorageRepository;

    /**
     * @var \Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStorageEntityManagerInterface
     */
    protected $cmsSlotStorageEntityManager;

    /**
     * @var \Spryker\Zed\CmsSlotStorage\Business\Mapper\CmsSlotStorageMapperInterface
     */
    protected $cmsSlotStorageMapper;

    /**
     * @param \Spryker\Zed\CmsSlotStorage\Dependency\Facade\CmsSlotStorageToCmsSlotFacadeInterface $cmsSlotFacade
     * @param \Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStorageRepositoryInterface $cmsSlotStorageRepository
     * @param \Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStorageEntityManagerInterface $cmsSlotStorageEntityManager
     * @param \Spryker\Zed\CmsSlotStorage\Business\Mapper\CmsSlotStorageMapperInterface $cmsSlotStorageMapper
     */
    public function __construct(
        CmsSlotStorageToCmsSlotFacadeInterface $cmsSlotFacade,
        CmsSlotStorageRepositoryInterface $cmsSlotStorageRepository,
        CmsSlotStorageEntityManagerInterface $cmsSlotStorageEntityManager,
        CmsSlotStorageMapperInterface $cmsSlotStorageMapper
    ) {
        $this->cmsSlotFacade = $cmsSlotFacade;
        $this->cmsSlotStorageRepository = $cmsSlotStorageRepository;
        $this->cmsSlotStorageEntityManager = $cmsSlotStorageEntityManager;
        $this->cmsSlotStorageMapper = $cmsSlotStorageMapper;
    }

    /**
     * @param int[] $cmsSlotIds
     *
     * @return void
     */
    public function publish(array $cmsSlotIds): void
    {
        $cmsSlotTransfers = $this->cmsSlotFacade->getCmsSlotsByCmsSlotIds($cmsSlotIds);
        $cmsSlotStorageEntities = $this->cmsSlotStorageRepository->getCmsSlotStorageEntitiesByCmsSlotKeys(
            $this->getCmsSlotKeys($cmsSlotTransfers)
        );
        $cmsSlotStorageEntities = $this->getMappedCmsSlotStorageEntitiesByKeys($cmsSlotStorageEntities);

        foreach ($cmsSlotTransfers as $cmsSlotTransfer) {
            if (!$cmsSlotTransfer->getIsActive() && isset($cmsSlotStorageEntities[$cmsSlotTransfer->getKey()])) {
                $idCmsSlotStorage = $cmsSlotStorageEntities[$cmsSlotTransfer->getKey()]->getIdCmsSlotStorage();
                $this->cmsSlotStorageEntityManager->deleteCmsSlotStorageById($idCmsSlotStorage);

                continue;
            }

            $this->cmsSlotStorageEntityManager->saveCmsSlotStorage(
                $this->cmsSlotStorageMapper->mapCmsSlotTransferToCmsSlotStorageTransfer(
                    new CmsSlotStorageTransfer(),
                    $cmsSlotTransfer
                )
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotTransfer[] $cmsSlotTransfers
     *
     * @return string[]
     */
    protected function getCmsSlotKeys(array $cmsSlotTransfers): array
    {
        return array_map(function (CmsSlotTransfer $cmsSlotTransfer) {
            return $cmsSlotTransfer->getKey();
        }, $cmsSlotTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCmsSlotStorageEntityTransfer[] $cmsSlotStorageEntities
     *
     * @return \Generated\Shared\Transfer\SpyCmsSlotStorageEntityTransfer[]
     */
    protected function getMappedCmsSlotStorageEntitiesByKeys(array $cmsSlotStorageEntities): array
    {
        $mappedCmsSlotStorageEntities = [];
        foreach ($cmsSlotStorageEntities as $cmsSlotStorageEntity) {
            $mappedCmsSlotStorageEntities[$cmsSlotStorageEntity->getCmsSlotKey()] = $cmsSlotStorageEntity;
        }

        return $mappedCmsSlotStorageEntities;
    }
}
