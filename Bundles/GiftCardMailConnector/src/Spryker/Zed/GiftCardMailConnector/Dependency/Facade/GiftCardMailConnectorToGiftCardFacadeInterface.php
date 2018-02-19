<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardMailConnector\Dependency\Facade;

interface GiftCardMailConnectorToGiftCardFacadeInterface
{
    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    public function findGiftCardByIdSalesOrderItem($idSalesOrderItem);

    /**
     * @param int $idGiftCard
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    public function findById($idGiftCard);
}
