<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyUserStorage\CompanyUserStorageConfig getConfig()
 * @method \Spryker\Zed\CompanyUserStorage\Business\CompanyUserStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyUserStorage\Communication\CompanyUserStorageCommunicationFactory getFactory()
 */
class CompanyUserStorageUnpublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $companyUserIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferIds($eventEntityTransfers);

        if (!$companyUserIds) {
            return;
        }

        $this->getFacade()->unpublishByCompanyUserIds($companyUserIds);
    }
}
