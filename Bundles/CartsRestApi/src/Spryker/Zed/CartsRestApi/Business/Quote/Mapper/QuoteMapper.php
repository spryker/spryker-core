<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote\Mapper;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Spryker\Zed\CartsRestApi\CartsRestApiConfig;

class QuoteMapper implements QuoteMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteUpdateRequestTransfer
     */
    public function mapQuoteTransferToQuoteUpdateRequestTransfer(QuoteTransfer $quoteTransfer): QuoteUpdateRequestTransfer
    {
        $quoteUpdateRequestTransfer = (new QuoteUpdateRequestTransfer())
            ->fromArray($quoteTransfer->modifiedToArray(), true);
        $quoteUpdateRequestAttributesTransfer = (new QuoteUpdateRequestAttributesTransfer())
            ->fromArray($quoteTransfer->modifiedToArray(), true);
        $quoteUpdateRequestTransfer->setQuoteUpdateRequestAttributes($quoteUpdateRequestAttributesTransfer);

        return $quoteUpdateRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $originalQuoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapQuoteTransferToOriginalQuoteTransfer(
        QuoteTransfer $quoteTransfer,
        QuoteTransfer $originalQuoteTransfer
    ): QuoteTransfer {
        return $originalQuoteTransfer->fromArray($quoteTransfer->modifiedToArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $originalQuoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function mapQuoteResponseErrorsToRestCodes(
        QuoteResponseTransfer $originalQuoteResponseTransfer
    ): QuoteResponseTransfer {
        $quoteResponseTransfer = new QuoteResponseTransfer();

        foreach ($originalQuoteResponseTransfer->getErrors() as $error) {
            $errorIdentifier = isset(CartsRestApiConfig::RESPONSE_ERROR_MAP[$error[MessageTransfer::VALUE]])
                ? CartsRestApiConfig::RESPONSE_ERROR_MAP[$error[MessageTransfer::VALUE]]
                : CartsRestApiConfig::RESPONSE_ERROR_MAP[$error->getMessage()];

            $quoteResponseTransfer->addError((new QuoteErrorTransfer())->setErrorIdentifier($errorIdentifier));
        }

        return $quoteResponseTransfer;
    }
}
