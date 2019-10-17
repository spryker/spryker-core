<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsSlotBlock\Business\Reader;


use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockRepositoryInterface;

class CmsSlotBlockRelationsReader implements CmsSlotBlockRelationsReaderInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockRepositoryInterface
     */
    protected $cmsSlotBlockRepository;

    /**
     * @param \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockRepositoryInterface $cmsSlotBlockRepository
     */
    public function __construct(CmsSlotBlockRepositoryInterface $cmsSlotBlockRepository)
    {
        $this->cmsSlotBlockRepository = $cmsSlotBlockRepository;
    }

    /**
     * @param int $idCmsSlotTemplate
     * @param int $idCmsSlot
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function getCmsSlotBlockCollection(int $idCmsSlotTemplate, int $idCmsSlot): CmsSlotBlockCollectionTransfer
    {
        return $this->cmsSlotBlockRepository->getCmsSlotBlocks($idCmsSlotTemplate, $idCmsSlot);
    }
}