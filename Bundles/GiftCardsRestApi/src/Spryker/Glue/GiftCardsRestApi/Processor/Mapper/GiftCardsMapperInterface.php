<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GiftCardsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\RestGiftCardsAttributesTransfer;

interface GiftCardsMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     * @param \Generated\Shared\Transfer\RestGiftCardsAttributesTransfer $restGiftCardsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestGiftCardsAttributesTransfer
     */
    public function mapGiftCardTransferToRestGiftCardsAttributesTransfer(
        GiftCardTransfer $giftCardTransfer,
        RestGiftCardsAttributesTransfer $restGiftCardsAttributesTransfer
    ): RestGiftCardsAttributesTransfer;
}
