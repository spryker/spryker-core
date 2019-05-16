<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;

class QuoteItemMapper implements QuoteItemMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapRestCartItemsAttributesTransferToQuoteTransfer(
        RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer
    ): QuoteTransfer {
        return (new QuoteTransfer())
            ->setUuid($restCartItemsAttributesTransfer->getQuoteUuid())
            ->setCustomerReference($restCartItemsAttributesTransfer->getCustomerReference());
    }
}
