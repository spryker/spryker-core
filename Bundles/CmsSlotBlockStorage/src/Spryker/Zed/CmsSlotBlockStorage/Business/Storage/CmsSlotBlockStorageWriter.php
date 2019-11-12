<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockStorage\Business\Storage;

use Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer;
use Generated\Shared\Transfer\CmsSlotBlockStorageTransfer;
use Spryker\Service\CmsSlotBlockStorage\CmsSlotBlockStorageServiceInterface;
use Spryker\Zed\CmsSlotBlockStorage\Persistence\CmsSlotBlockStorageEntityManagerInterface;
use Spryker\Zed\CmsSlotBlockStorage\Persistence\CmsSlotBlockStorageRepositoryInterface;

class CmsSlotBlockStorageWriter implements CmsSlotBlockStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlockStorage\Persistence\CmsSlotBlockStorageRepositoryInterface
     */
    protected $cmsSlotBlockStorageRepository;

    /**
     * @var \Spryker\Zed\CmsSlotBlockStorage\Persistence\CmsSlotBlockStorageEntityManagerInterface
     */
    protected $cmsSlotBlockStorageEntityManager;

    /**
     * @var \Spryker\Service\CmsSlotBlockStorage\CmsSlotBlockStorageServiceInterface
     */
    protected $cmsSlotBlockStorageService;

    /**
     * @param \Spryker\Zed\CmsSlotBlockStorage\Persistence\CmsSlotBlockStorageRepositoryInterface $cmsSlotBlockStorageRepository
     * @param \Spryker\Zed\CmsSlotBlockStorage\Persistence\CmsSlotBlockStorageEntityManagerInterface $cmsSlotBlockStorageEntityManager
     * @param \Spryker\Service\CmsSlotBlockStorage\CmsSlotBlockStorageServiceInterface $cmsSlotBlockStorageService
     */
    public function __construct(
        CmsSlotBlockStorageRepositoryInterface $cmsSlotBlockStorageRepository,
        CmsSlotBlockStorageEntityManagerInterface $cmsSlotBlockStorageEntityManager,
        CmsSlotBlockStorageServiceInterface $cmsSlotBlockStorageService
    ) {
        $this->cmsSlotBlockStorageRepository = $cmsSlotBlockStorageRepository;
        $this->cmsSlotBlockStorageEntityManager = $cmsSlotBlockStorageEntityManager;
        $this->cmsSlotBlockStorageService = $cmsSlotBlockStorageService;
    }

    /**
     * @param string[] $cmsSlotBlockIds
     *
     * @return void
     */
    public function publish(array $cmsSlotBlockIds): void
    {
        $cmsSlotBlockStorageTransfers = $this->cmsSlotBlockStorageRepository
            ->getCmsSlotBlockStorageTransfersByCmsSlotBlockIds($cmsSlotBlockIds);

        foreach ($cmsSlotBlockStorageTransfers as $cmsSlotBlockStorageTransfer) {
            $cmsSlotBlockStorageDataTransfer = $this->getCmsSlotBlockStorageDataTransfer($cmsSlotBlockStorageTransfer);

            if (!$cmsSlotBlockStorageDataTransfer->getCmsBlocks()) {
                $this->cmsSlotBlockStorageEntityManager->deleteCmsSlotBlockStorage(
                    $cmsSlotBlockStorageTransfer
                );

                continue;
            }

            $this->storeDataSet($cmsSlotBlockStorageTransfer, $cmsSlotBlockStorageDataTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
     * @param \Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer $cmsSlotBlockStorageDataTransfer
     *
     * @return void
     */
    protected function storeDataSet(
        CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer,
        CmsSlotBlockStorageDataTransfer $cmsSlotBlockStorageDataTransfer
    ): void {
        $cmsSlotBlockStorageTransfer->setSlotTemplateKey(
            $this->getCmsSlotBlockStorageKey($cmsSlotBlockStorageTransfer)
        );
        $cmsSlotBlockStorageTransfer->setData($cmsSlotBlockStorageDataTransfer);
        $this->cmsSlotBlockStorageEntityManager->saveCmsSlotBlockStorage($cmsSlotBlockStorageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockStorageDataTransfer
     */
    protected function getCmsSlotBlockStorageDataTransfer(
        CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
    ): CmsSlotBlockStorageDataTransfer {
        return $this->cmsSlotBlockStorageRepository
            ->getCmsSlotBlockStorageDataTransferByCmsSlotBlockStorageTransfer(
                $cmsSlotBlockStorageTransfer
            );
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
     *
     * @return string
     */
    protected function getCmsSlotBlockStorageKey(
        CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
    ): string {
        return $this->cmsSlotBlockStorageService->generateKey(
            $cmsSlotBlockStorageTransfer->getTemplatePath(),
            $cmsSlotBlockStorageTransfer->getSlotKey()
        );
    }
}
