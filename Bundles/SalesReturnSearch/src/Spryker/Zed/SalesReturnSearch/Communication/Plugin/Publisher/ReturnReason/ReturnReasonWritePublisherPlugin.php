<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnSearch\Communication\Plugin\Publisher\ReturnReason;

use Spryker\Shared\SalesReturnSearch\SalesReturnSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\SalesReturnSearch\SalesReturnSearchConfig getConfig()
 * @method \Spryker\Zed\SalesReturnSearch\Business\SalesReturnSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesReturnSearch\Communication\SalesReturnSearchCommunicationFactory getFactory()
 */
class ReturnReasonWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getFacade()->writeCollectionByReturnReasonEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            SalesReturnSearchConfig::RETURN_REASON_PUBLISH_WRITE,
            SalesReturnSearchConfig::ENTITY_SPY_SALES_RETURN_REASON_CREATE,
            SalesReturnSearchConfig::ENTITY_SPY_SALES_RETURN_REASON_UPDATE,
        ];
    }
}
