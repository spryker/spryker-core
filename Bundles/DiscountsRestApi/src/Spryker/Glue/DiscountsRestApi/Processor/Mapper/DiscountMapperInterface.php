<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DiscountsRestApi\Processor\Mapper;

interface DiscountMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer[] $quoteRequestTransfers
     * @param \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer[] $restQuoteRequestsAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer[]
     */
    public function mapDiscountTransfersToRestQuoteRequestsAttributesTransfers(
        array $quoteRequestTransfers,
        array $restQuoteRequestsAttributesTransfers
    ): array;
}
