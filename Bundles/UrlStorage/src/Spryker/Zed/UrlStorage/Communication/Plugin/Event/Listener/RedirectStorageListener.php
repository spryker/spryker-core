<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\Url\Dependency\UrlEvents;

/**
 * @method \Spryker\Zed\UrlStorage\Persistence\UrlStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\UrlStorage\Communication\UrlStorageCommunicationFactory getFactory()
 */
class RedirectStorageListener extends AbstractRedirectStorageListener implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();
        $redirectIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);

        if ($eventName === UrlEvents::ENTITY_SPY_URL_REDIRECT_CREATE || $eventName === UrlEvents::ENTITY_SPY_URL_REDIRECT_UPDATE) {
            $this->publish($redirectIds);
        } elseif ($eventName === UrlEvents::ENTITY_SPY_URL_REDIRECT_DELETE) {
            $this->unpublish($redirectIds);
        }
    }
}
