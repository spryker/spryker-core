<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DiscountsRestApi\Plugin\QuoteRequestsRestApi;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\QuoteRequestsRestApiExtension\Dependency\Plugin\RestQuoteRequestAttributesExpanderPluginInterface;

/**
 * @method \Spryker\Glue\DiscountsRestApi\DiscountsRestApiFactory getFactory()
 */
class DiscountsRestQuoteRequestAttributesExpanderPlugin extends AbstractPlugin implements RestQuoteRequestAttributesExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands restQuoteRequestsAttributesTransfer with discount data.
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
            ->createDiscountMapper()
            ->mapDiscountTransfersToRestQuoteRequestsAttributesTransfers(
                $quoteRequestTransfers,
                $restQuoteRequestsAttributesTransfers
            );
    }
}
