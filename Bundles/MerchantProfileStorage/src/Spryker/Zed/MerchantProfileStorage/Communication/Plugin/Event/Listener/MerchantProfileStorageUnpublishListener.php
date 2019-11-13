<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantProfileStorage\Communication\MerchantProfileStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileStorage\Business\MerchantProfileStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProfileStorage\MerchantProfileStorageConfig getConfig()
 */
class MerchantProfileStorageUnpublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * {@inheritDoc}
     * - Handles merchant profile delete events.
     *
     * @api
     *
     * @param array $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $merchantProfileIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);

        $this->getFacade()->unpublish($merchantProfileIds);
    }
}
