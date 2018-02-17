<?php

namespace Spryker\Zed\GiftCard\Business\ActualValueHydrator;

use Generated\Shared\Transfer\GiftCardTransfer;

interface GiftCardActualValueHydratorInterface
{
    /**
     * @return GiftCardTransfer
     */
    public function hydrate(GiftCardTransfer $giftCardTransfer);
}