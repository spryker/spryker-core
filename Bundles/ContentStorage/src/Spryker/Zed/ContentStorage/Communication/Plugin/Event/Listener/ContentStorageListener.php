<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Communication\Plugin\Event\Listener;

use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ContentStorage\Business\ContentStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ContentStorage\Communication\ContentStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentStorage\ContentStorageConfig getConfig()
 */
class ContentStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $transfers
     * @param string $eventName
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function handleBulk(array $transfers, $eventName): void
    {
        if (Propel::getConnection()->inTransaction()) {
            throw new PropelException('This operation is not allowed inside of transaction');
        }

        $contentIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($transfers);

        $this->getFacade()->publish($contentIds);
    }
}
