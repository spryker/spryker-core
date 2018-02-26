<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardMailConnector\Dependency\Facade;

class GiftCardMailConnectorToGiftCardFacadeBridge implements GiftCardMailConnectorToGiftCardFacadeInterface
{
    /**
     * @var \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface
     */
    protected $giftCardFacade;

    /**
     * @param \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface $giftCardFacade
     */
    public function __construct($giftCardFacade)
    {
        $this->giftCardFacade = $giftCardFacade;
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    public function findGiftCardByIdSalesOrderItem($idSalesOrderItem)
    {
        return $this->giftCardFacade->findGiftCardByIdSalesOrderItem($idSalesOrderItem);
    }

    /**
     * @param int $idGiftCard
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    public function findById($idGiftCard)
    {
        return $this->giftCardFacade->findById($idGiftCard);
    }
}
