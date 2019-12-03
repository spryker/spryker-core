<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsSlotBlockStorage\Communication\CmsSlotBlockStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsSlotBlockStorage\CmsSlotBlockStorageConfig getConfig()
 * @method \Spryker\Zed\CmsSlotBlockStorage\Business\CmsSlotBlockStorageFacadeInterface getFacade()
 */
class CmsSlotBlockStoragePublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $cmsSlotBlockTransfers = $this->mapEventEntityTransfersToCmsSlotBlockTransfers($eventTransfers);
        $this->getFacade()->publishByCmsSlotBlocks($cmsSlotBlockTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockTransfer[]
     */
    protected function mapEventEntityTransfersToCmsSlotBlockTransfers(array $eventEntityTransfers): array
    {
        $cmsSlotBlockTransfers = [];

        foreach ($eventEntityTransfers as $eventEntityTransfer) {
            $cmsSlotBlockTransfer = (new CmsSlotBlockTransfer())
                ->setIdCmsSlotBlock((string)$eventEntityTransfer->getId())
                ->setIdSlotTemplate(
                    $eventEntityTransfer->getAdditionalValues()[CmsSlotBlockTransfer::ID_SLOT_TEMPLATE] ?? null
                )
                ->setIdSlot($eventEntityTransfer->getAdditionalValues()[CmsSlotBlockTransfer::ID_SLOT] ?? null);

            $cmsSlotBlockTransfers[] = $cmsSlotBlockTransfer;
        }

        return $cmsSlotBlockTransfers;
    }
}
