<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOffersRestApi\Plugin\QuoteRequestsRestApi;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\QuoteRequestsRestApiExtension\Dependency\Plugin\RestQuoteRequestAttributesExpanderPluginInterface;

/**
 * @method \Spryker\Glue\MerchantProductOffersRestApi\MerchantProductOffersRestApiFactory getFactory()
 */
class MerchantProductOffersRestQuoteRequestAttributesExpanderPlugin extends AbstractPlugin implements RestQuoteRequestAttributesExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands RestQuoteRequestItemTransfer with merchants data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer[] $restQuoteRequestsAttributesTransfers
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer[] $quoteRequestTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer[]
     */
    public function expand(
        array $restQuoteRequestsAttributesTransfers,
        array $quoteRequestTransfers,
        string $localeName
    ): array {
        return $this->getFactory()
            ->createQuoteRequestItemExpander()
            ->expandRestQuoteRequestItemWithMerchantProductOffers(
                $restQuoteRequestsAttributesTransfers,
                $quoteRequestTransfers,
                $localeName
            );
    }
}
