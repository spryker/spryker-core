<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface QuoteRequestMapperInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\QuoteRequestTransfer> $quoteRequestTransfers
     * @param array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer> $restQuoteRequestsAttributesTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param bool $isLatestVersionVisible
     *
     * @return array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer>
     */
    public function mapQuoteRequestTransfersToRestQuoteRequestsAttributesTransfers(
        array $quoteRequestTransfers,
        array $restQuoteRequestsAttributesTransfers,
        RestRequestInterface $restRequest,
        bool $isLatestVersionVisible = true
    ): array;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function mapRestRequestToQuoteRequestTransfer(
        RestRequestInterface $restRequestTransfer,
        QuoteRequestTransfer $quoteRequestTransfer
    ): QuoteRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestsRequestAttributesTransfer $restQuoteRequestsRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function mapRestQuoteRequestsRequestAttributesTransferToQuoteRequestTransfer(
        RestQuoteRequestsRequestAttributesTransfer $restQuoteRequestsRequestAttributesTransfer,
        QuoteRequestTransfer $quoteRequestTransfer
    ): QuoteRequestTransfer;
}
