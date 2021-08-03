<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Expander;

interface QuoteRequestItemExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer[] $restQuoteRequestsAttributesTransfers
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer[] $quoteRequestTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer[]
     */
    public function expandRestQuoteRequestItemWithShipments(
        array $restQuoteRequestsAttributesTransfers,
        array $quoteRequestTransfers,
        string $localeName
    ): array;
}
