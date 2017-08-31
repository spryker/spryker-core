<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business;

use Generated\Shared\Transfer\GiftCardTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\GiftCard\Business\GiftCardBusinessFactory getFactory()
 */
class GiftCardFacade extends AbstractFacade implements GiftCardFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return void
     */
    public function create(GiftCardTransfer $giftCardTransfer)
    {
        $this
            ->getFactory()
            ->createGiftCardCreator()
            ->create($giftCardTransfer);
    }

    /**
     * @api
     *
     * @param int $idGiftCard
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    public function findById($idGiftCard)
    {
        return $this
            ->getFactory()
            ->createGiftCardReader()
            ->findById($idGiftCard);
    }

}
