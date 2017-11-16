<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardMailConnector\Communication\Plugin\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByItemInterface;

/**
 * @method \Spryker\Zed\GiftCardMailConnector\Business\GiftCardMailConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\GiftCardMailConnector\Communication\GiftCardMailConnectorCommunicationFactory getFactory()
 */
class ShipGiftCardByEmailCommandPlugin extends AbstractPlugin implements CommandByItemInterface
{
    /**
     * Specification:
     * - Finds Gift Cards in the provided order
     * - Send Gift Cards info via email
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(SpySalesOrderItem $orderItem, ReadOnlyArrayObject $data)
    {
        $this->getFacade()->deliverGiftCardByEmail($orderItem->getIdSalesOrderItem());

        return [];
    }
}
