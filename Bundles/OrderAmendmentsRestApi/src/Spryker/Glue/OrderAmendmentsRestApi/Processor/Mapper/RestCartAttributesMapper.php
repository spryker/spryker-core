<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartsAttributesTransfer;

class RestCartAttributesMapper implements RestCartAttributesMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCartsAttributesTransfer
     */
    public function mapQuoteTransferToRestCartAttributesTransfer(
        QuoteTransfer $quoteTransfer,
        RestCartsAttributesTransfer $restCartsAttributesTransfer
    ): RestCartsAttributesTransfer {
        if ($quoteTransfer->getAmendmentOrderReference()) {
            $restCartsAttributesTransfer->setAmendmentOrderReference($quoteTransfer->getAmendmentOrderReferenceOrFail());
        }

        return $restCartsAttributesTransfer;
    }
}
