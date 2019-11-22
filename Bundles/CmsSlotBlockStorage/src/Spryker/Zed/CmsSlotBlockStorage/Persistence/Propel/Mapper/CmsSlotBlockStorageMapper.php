<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer;
use Generated\Shared\Transfer\CmsSlotBlockStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\CmsSlot\Persistence\Map\SpyCmsSlotTableMap;
use Orm\Zed\CmsSlot\Persistence\Map\SpyCmsSlotTemplateTableMap;
use Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlock;
use Orm\Zed\CmsSlotBlockStorage\Persistence\SpyCmsSlotBlockStorage;
use Spryker\Shared\CmsSlotBlockStorage\CmsSlotBlockStorageConfig;
use Spryker\Zed\CmsSlotBlockStorage\Dependency\Service\CmsSlotBlockStorageToUtilEncodingServiceInterface;

class CmsSlotBlockStorageMapper
{
    /**
     * @var \Spryker\Zed\CmsSlotBlockStorage\Dependency\Service\CmsSlotBlockStorageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\CmsSlotBlockStorage\Dependency\Service\CmsSlotBlockStorageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(CmsSlotBlockStorageToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlock $cmsSlotBlockEntity
     * @param \Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer $cmsSlotBlockStorageDataTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer
     */
    public function mapCmsSlotBlockEntityToCmsSlotBlockStorageDataTransfer(
        SpyCmsSlotBlock $cmsSlotBlockEntity,
        CmsSlotBlockStorageDataTransfer $cmsSlotBlockStorageDataTransfer
    ): CmsSlotBlockStorageDataTransfer {
        $cmsSlotBlockStorageDataTransfer->addCmsBlock([
            CmsSlotBlockStorageConfig::BLOCK_DATA_KEY_BLOCK_KEY => $cmsSlotBlockEntity->getCmsBlock()->getKey(),
            CmsSlotBlockStorageConfig::BLOCK_DATA_KEY_CONDITIONS => $this->utilEncodingService->decodeJson($cmsSlotBlockEntity->getConditions()),
        ]);

        return $cmsSlotBlockStorageDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
     * @param \Orm\Zed\CmsSlotBlockStorage\Persistence\SpyCmsSlotBlockStorage $cmsSlotBlockStorageEntity
     *
     * @return \Orm\Zed\CmsSlotBlockStorage\Persistence\SpyCmsSlotBlockStorage
     */
    public function mapCmsSlotBlockStorageTransferToCmsSlotBlockStorageEntity(
        CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer,
        SpyCmsSlotBlockStorage $cmsSlotBlockStorageEntity
    ): SpyCmsSlotBlockStorage {
        $cmsSlotBlockStorageEntity->setFkCmsSlot($cmsSlotBlockStorageTransfer->getIdCmsSlot());
        $cmsSlotBlockStorageEntity->setFkCmsSlotTemplate($cmsSlotBlockStorageTransfer->getIdCmsSlotTemplate());
        $cmsSlotBlockStorageEntity->setSlotTemplateKey($cmsSlotBlockStorageTransfer->getSlotTemplateKey());

        return $cmsSlotBlockStorageEntity;
    }

    /**
     * @param \Orm\Zed\CmsSlotBlockStorage\Persistence\SpyCmsSlotBlockStorage $cmsSlotBlockStorageEntity
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer
     */
    public function mapCmsSlotBlockStorageEntityToSynchronizationDataTransfer(
        SpyCmsSlotBlockStorage $cmsSlotBlockStorageEntity
    ): SynchronizationDataTransfer {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        /** @var string $data */
        $data = $cmsSlotBlockStorageEntity->getData();
        $synchronizationDataTransfer->setData($data);
        $synchronizationDataTransfer->setKey($cmsSlotBlockStorageEntity->getKey());

        return $synchronizationDataTransfer;
    }

    /**
     * @param array $cmsSlotWithSlotTemplateCombination
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockStorageTransfer
     */
    public function mapCmsSlotWithTemplateCombinationToCmsSlotBlockStorageTransfer(
        array $cmsSlotWithSlotTemplateCombination
    ): CmsSlotBlockStorageTransfer {
        return (new CmsSlotBlockStorageTransfer())
            ->setIdCmsSlot($cmsSlotWithSlotTemplateCombination[SpyCmsSlotTableMap::COL_ID_CMS_SLOT])
            ->setIdCmsSlotTemplate($cmsSlotWithSlotTemplateCombination[SpyCmsSlotTemplateTableMap::COL_ID_CMS_SLOT_TEMPLATE])
            ->setSlotKey($cmsSlotWithSlotTemplateCombination[SpyCmsSlotTableMap::COL_KEY])
            ->setTemplatePath($cmsSlotWithSlotTemplateCombination[SpyCmsSlotTemplateTableMap::COL_PATH]);
    }
}
