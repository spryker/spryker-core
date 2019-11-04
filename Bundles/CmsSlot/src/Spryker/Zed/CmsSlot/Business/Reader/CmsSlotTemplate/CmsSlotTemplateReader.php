<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business\Reader\CmsSlotTemplate;

use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Spryker\Zed\CmsSlot\Business\Exception\MissingCmsSlotTemplateException;
use Spryker\Zed\CmsSlot\Persistence\CmsSlotRepositoryInterface;

class CmsSlotTemplateReader implements CmsSlotTemplateReaderInterface
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
     * @param int $idCmsSlotTemplate
     *
     * @throws \Spryker\Zed\CmsSlot\Business\Exception\MissingCmsSlotTemplateException
     *
     * @return \Generated\Shared\Transfer\CmsSlotTemplateTransfer
     */
    public function getTemplateById(int $idCmsSlotTemplate): CmsSlotTemplateTransfer
    {
        $cmsSlotTemplateTransfer = $this->cmsSlotRepository->findCmsSlotTemplateById($idCmsSlotTemplate);

        if (!$idCmsSlotTemplate) {
            throw new MissingCmsSlotTemplateException(
                sprintf(
                    'CMS Slot Template with id "%d" not found.',
                    $idCmsSlotTemplate
                )
            );
        }

        return $cmsSlotTemplateTransfer;
    }
}
