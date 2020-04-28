<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GiftCardsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\RestGiftCardsAttributesTransfer;

class GiftCardsMapper implements GiftCardsMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardsTransfer
     * @param \Generated\Shared\Transfer\RestGiftCardsAttributesTransfer $restGiftCardsAttributes
     *
     * @return \Generated\Shared\Transfer\RestGiftCardsAttributesTransfer
     */
    public function mapGiftCardDataToRestGiftCardsAttributesTransfer(
        GiftCardTransfer $giftCardsTransfer,
        RestGiftCardsAttributesTransfer $restGiftCardsAttributes
    ): RestGiftCardsAttributesTransfer {
        return $restGiftCardsAttributes->fromArray($giftCardsTransfer->toArray(), true);
    }
}
