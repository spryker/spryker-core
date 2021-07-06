<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface QuoteRequestMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer[] $quoteRequestTransfers
     * @param \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer[] $restQuoteRequestsAttributesTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer[]
     */
    public function mapQuoteRequestTransfersToRestQuoteRequestsAttributesTransfers(
        array $quoteRequestTransfers,
        array $restQuoteRequestsAttributesTransfers,
        string $localeName
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
}
