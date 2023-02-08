<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage\Communication\Plugin\Publisher\Customer;

use Spryker\Shared\CustomerStorage\CustomerStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\CustomerStorage\Communication\CustomerStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CustomerStorage\Business\CustomerStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerStorage\CustomerStorageConfig getConfig()
 */
class CustomerInvalidatedWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Used in case if customer was invalidated or customer's password was changed.
     * - Publishes customer data to storage based on customer publish event.
     *
     * @api
     *
     * @param array<int, \Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        $this->getFacade()->writeCustomerInvalidatedStorageCollectionByCustomerEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<int, string>
     */
    public function getSubscribedEvents(): array
    {
        return [
            CustomerStorageConfig::ENTITY_SPY_CUSTOMER_UPDATE,
        ];
    }
}
