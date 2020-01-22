<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage\Communication\Plugin\Event\Listener;

use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated Use `Spryker\Zed\CustomerAccessStorage\Communication\Plugin\Event\Listener\CustomerAccessStorageBulkListener` instead.
 *
 * @method \Spryker\Zed\CustomerAccessStorage\Business\CustomerAccessStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerAccessStorage\Communication\CustomerAccessStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CustomerAccessStorage\CustomerAccessStorageConfig getConfig()
 */
class CustomerAccessStorageListener extends AbstractPlugin implements EventHandlerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     * @param string $eventName
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function handle(TransferInterface $eventTransfer, $eventName): void
    {
        if (Propel::getConnection()->inTransaction()) {
            throw new PropelException('This operation is not allowed inside of transaction');
        }

        $this->getFacade()->publish();
    }
}
