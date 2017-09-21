<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardMailConnector\Dependency\QueryContainer;

use Spryker\Zed\GiftCardMailConnector\Dependency\QueryContainer\GiftCardMailConnectorToGiftCardQueryContainerInterface;

class GiftCardMailConnectorToGiftCardQueryContainerBridge implements GiftCardMailConnectorToGiftCardQueryContainerInterface
{

    /**
     * @var \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface
     */
    protected $giftCardQueryContainer;

    /**
     * @param \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface $giftCardQueryContainer
     */
    public function __construct($giftCardQueryContainer)
    {
        $this->giftCardQueryContainer = $giftCardQueryContainer;
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCardQuery
     */
    public function queryGiftCardOrderItemMetadata($idSalesOrderItem)
    {
        return $this->giftCardQueryContainer->queryGiftCardOrderItemMetadata($idSalesOrderItem);
    }

}
