<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestsRestApi\Business\Mapper;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

class QuoteRequestResponseMapper implements QuoteRequestResponseMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function mapErrorMessagesFromQuoteResponseToQuoteRequestResponse(
        QuoteResponseTransfer $quoteResponseTransfer,
        QuoteRequestResponseTransfer $quoteRequestResponseTransfer
    ): QuoteRequestResponseTransfer {
        foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
            $quoteRequestResponseTransfer->addMessage(
                $this->mapQuoteErrorToMessage($quoteErrorTransfer, new MessageTransfer()),
            );
        }

        return $quoteRequestResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteErrorTransfer $quoteErrorTransfer
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function mapQuoteErrorToMessage(QuoteErrorTransfer $quoteErrorTransfer, MessageTransfer $messageTransfer): MessageTransfer
    {
        return $messageTransfer->setValue($quoteErrorTransfer->getErrorIdentifier());
    }
}
