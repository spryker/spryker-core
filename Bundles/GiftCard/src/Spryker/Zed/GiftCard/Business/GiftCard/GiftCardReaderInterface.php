<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\GiftCard;

interface GiftCardReaderInterface
{

    /**
     * @param int $idGiftCard
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    public function findById($idGiftCard);

    /**
     * @param int $idSalesOrderItem
     *
     * @throws \Spryker\Zed\GiftCard\Business\Exception\GiftCardSalesMetadataNotFoundException
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard
     */
    public function getGiftCardOrderItemMetadata($idSalesOrderItem);

    /**
     * @param string $code
     *
     * @return bool
     */
    public function isPresent($code);

    /**
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    public function findByCode($code);
    
}
