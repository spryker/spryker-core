<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockStorage\Business\Storage;

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
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfers
     *
     * @return void
     */
    public function publish(array $cmsSlotBlockTransfers): void
    {
        $cmsSlotBlockStorageTransfers = $this->cmsSlotBlockStorageRepository
            ->getCmsSlotBlockStorageTransfersByCmsSlotBlocks($cmsSlotBlockTransfers);

        foreach ($cmsSlotBlockStorageTransfers as $cmsSlotBlockStorageTransfer) {
            $cmsSlotBlockCollectionTransfer = $this->cmsSlotBlockStorageRepository
                ->getCmsSlotBlockCollectionByCmsSlotBlockStorageTransfer(
                    $cmsSlotBlockStorageTransfer
                );

            if ($cmsSlotBlockCollectionTransfer->getCmsSlotBlocks()->count() === 0) {
                $this->cmsSlotBlockStorageEntityManager->deleteCmsSlotBlockStorage(
                    $cmsSlotBlockStorageTransfer
                );

                continue;
            }

            $cmsSlotBlockStorageTransfer->setData($cmsSlotBlockCollectionTransfer)
                ->setSlotTemplateKey(
                    $this->getCmsSlotBlockStorageKey($cmsSlotBlockStorageTransfer)
                );

            $this->cmsSlotBlockStorageEntityManager->saveCmsSlotBlockStorage($cmsSlotBlockStorageTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
     *
     * @return string
     */
    protected function getCmsSlotBlockStorageKey(
        CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
    ): string {
        return $this->cmsSlotBlockStorageService->generateSlotTemplateKey(
            $cmsSlotBlockStorageTransfer->getTemplatePath(),
            $cmsSlotBlockStorageTransfer->getSlotKey()
        );
    }
}
