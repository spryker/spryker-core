<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business\CmsSlot;

use Generated\Shared\Transfer\CmsSlotTransfer;
use Spryker\Zed\CmsSlot\Business\Exception\MissingCmsSlotException;
use Spryker\Zed\CmsSlot\Persistence\CmsSlotRepositoryInterface;

class CmsSlotReader implements CmsSlotReaderInterface
{
    /**
     * @var \Spryker\Zed\CmsSlot\Persistence\CmsSlotRepositoryInterface
     */
    protected $cmsSlotRepository;

    /**
     * @param \Spryker\Zed\CmsSlot\Persistence\CmsSlotRepositoryInterface $cmsSlotRepository
     */
    public function __construct(CmsSlotRepositoryInterface $cmsSlotRepository)
    {
        $this->cmsSlotRepository = $cmsSlotRepository;
    }

    /**
     * @param int $idCmsSlot
     *
     * @throws \Spryker\Zed\CmsSlot\Business\Exception\MissingCmsSlotException
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer
     */
    public function getCmsSlotById(int $idCmsSlot): CmsSlotTransfer
    {
        $cmsSlotTransfer = $this->cmsSlotRepository->findCmsSlotById($idCmsSlot);

        if (!$cmsSlotTransfer) {
            throw new MissingCmsSlotException(
                sprintf(
                    'CMS Slot with id "%d" not found.',
                    $idCmsSlot
                )
            );
        }

        return $cmsSlotTransfer;
    }
}
