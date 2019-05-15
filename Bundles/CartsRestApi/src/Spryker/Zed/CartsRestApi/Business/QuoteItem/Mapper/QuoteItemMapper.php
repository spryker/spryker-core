<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\QuoteItem\Mapper;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Zed\CartsRestApi\CartsRestApiConfig;

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

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function mapQuoteResponseErrorsToRestCodes(
        QuoteResponseTransfer $quoteResponseTransfer
    ): QuoteResponseTransfer {
        $errorCodes = [];
        foreach ($quoteResponseTransfer->getErrors() as $error) {
            $errorCodes[] = isset($error[MessageTransfer::VALUE])
                ? CartsRestApiConfig::RESPONSE_ERROR_MAP[$error[MessageTransfer::VALUE]]
                : $error->getMessage();
        }

        $quoteResponseTransfer->setErrorCodes($errorCodes);

        return $quoteResponseTransfer;
    }
}
