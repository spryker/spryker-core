<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardMailConnector\Dependency\QueryContainer;

interface GiftCardMailConnectorToGiftCardQueryContainerInterface
{

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCardQuery
     */
    public function queryGiftCardOrderItemMetadata($idSalesOrderItem);

}
