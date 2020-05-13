<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnPageSearch\Communication\Plugin\Publisher\ReturnReason;

use Spryker\Shared\SalesReturnPageSearch\SalesReturnPageSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\SalesReturnPageSearch\SalesReturnPageSearchConfig getConfig()
 * @method \Spryker\Zed\SalesReturnPageSearch\Business\SalesReturnPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesReturnPageSearch\Communication\SalesReturnPageSearchCommunicationFactory getFactory()
 */
class ReturnReasonWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
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
    public function handleBulk(array $eventTransfers, $eventName): void
    {
        $this->getFacade()->writeCollectionByReturnReasonEvents($eventTransfers);
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
            SalesReturnPageSearchConfig::RETURN_REASON_PUBLISH_WRITE,
            SalesReturnPageSearchConfig::ENTITY_SPY_RETURN_REASON_CREATE,
            SalesReturnPageSearchConfig::ENTITY_SPY_RETURN_REASON_UPDATE,
        ];
    }
}
