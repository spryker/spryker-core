<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestAgentsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\RestAgentQuoteRequestsRequestAttributesTransfer;

class QuoteRequestMapper implements QuoteRequestMapperInterface
{
    /**
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @param \Generated\Shared\Transfer\RestAgentQuoteRequestsRequestAttributesTransfer $restAgentQuoteRequestsRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function mapRestAgentQuoteRequestsRequestAttributesTransferToQuoteRequestTransfer(
        RestAgentQuoteRequestsRequestAttributesTransfer $restAgentQuoteRequestsRequestAttributesTransfer,
        QuoteRequestTransfer $quoteRequestTransfer
    ): QuoteRequestTransfer {
        $quoteRequestVersionTransfer = (new QuoteRequestVersionTransfer())
            ->fromArray($quoteRequestTransfer->getLatestVersionOrFail()->toArray())
            ->fromArray($restAgentQuoteRequestsRequestAttributesTransfer->toArray(), true);

        $quoteRequestVersionTransfer = $this->mapUnitPricesToQuoteItems(
            $restAgentQuoteRequestsRequestAttributesTransfer,
            $quoteRequestVersionTransfer,
        );

        return $quoteRequestTransfer
            ->fromArray($restAgentQuoteRequestsRequestAttributesTransfer->toArray(), true)
            ->setLatestVersion($quoteRequestVersionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestAgentQuoteRequestsRequestAttributesTransfer $restAgentQuoteRequestsRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    protected function mapUnitPricesToQuoteItems(
        RestAgentQuoteRequestsRequestAttributesTransfer $restAgentQuoteRequestsRequestAttributesTransfer,
        QuoteRequestVersionTransfer $quoteRequestVersionTransfer
    ): QuoteRequestVersionTransfer {
        foreach ($quoteRequestVersionTransfer->getQuoteOrFail()->getItems() as $itemTransfer) {
            $newItemPrice = $restAgentQuoteRequestsRequestAttributesTransfer->getUnitPriceMap()[$itemTransfer->getGroupKeyOrFail()] ?? null;
            if ($newItemPrice === null) {
                continue;
            }

            if ($quoteRequestVersionTransfer->getQuoteOrFail()->getPriceModeOrFail() === static::PRICE_MODE_GROSS) {
                $itemTransfer->setSourceUnitGrossPrice($newItemPrice);

                continue;
            }

            $itemTransfer->setSourceUnitNetPrice($newItemPrice);
        }

        return $quoteRequestVersionTransfer;
    }
}
