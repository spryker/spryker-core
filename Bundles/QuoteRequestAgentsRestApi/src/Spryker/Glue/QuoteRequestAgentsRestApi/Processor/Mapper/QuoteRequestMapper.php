<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestAgentsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestAgentQuoteRequestsRequestAttributesTransfer;

class QuoteRequestMapper implements QuoteRequestMapperInterface
{
    /**
     * @inheritDoc
     */
    public function mapRestAgentQuoteRequestsRequestAttributesTransferToQuoteRequestTransfer(
        RestAgentQuoteRequestsRequestAttributesTransfer $restAgentQuoteRequestsRequestAttributesTransfer,
        QuoteRequestTransfer $quoteRequestTransfer
    ): QuoteRequestTransfer {
        $quoteTransfer = (new QuoteTransfer())
            ->setUuid($restAgentQuoteRequestsRequestAttributesTransfer->getCartUuid());

        if ($quoteRequestTransfer->getLatestVersion() === null) {
            return $quoteRequestTransfer;
        }

        $quoteRequestVersionTransfer = ($quoteRequestTransfer->getLatestVersion())
            ->setMetadata($restAgentQuoteRequestsRequestAttributesTransfer->getMetadata())
            ->setQuote($quoteTransfer);

        return $quoteRequestTransfer
            ->setLatestVersion($quoteRequestVersionTransfer);
    }
}
