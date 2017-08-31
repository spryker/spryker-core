<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\GiftCard;

use Generated\Shared\Transfer\GiftCardTransfer;

interface GiftCardCreatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return void
     */
    public function create(GiftCardTransfer $giftCardTransfer);

}
