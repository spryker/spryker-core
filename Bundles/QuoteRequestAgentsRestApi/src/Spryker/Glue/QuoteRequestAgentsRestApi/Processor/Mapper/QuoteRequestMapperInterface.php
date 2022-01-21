<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestAgentsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\RestAgentQuoteRequestsRequestAttributesTransfer;

interface QuoteRequestMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestAgentQuoteRequestsRequestAttributesTransfer $restAgentQuoteRequestsRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function mapRestAgentQuoteRequestsRequestAttributesTransferToQuoteRequestTransfer(
        RestAgentQuoteRequestsRequestAttributesTransfer $restAgentQuoteRequestsRequestAttributesTransfer,
        QuoteRequestTransfer $quoteRequestTransfer
    ): QuoteRequestTransfer;
}
