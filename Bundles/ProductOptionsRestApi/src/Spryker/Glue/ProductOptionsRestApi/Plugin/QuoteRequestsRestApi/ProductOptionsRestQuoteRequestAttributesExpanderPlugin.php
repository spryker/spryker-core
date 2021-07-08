<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Plugin\QuoteRequestsRestApi;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\QuoteRequestsRestApiExtension\Dependency\Plugin\RestQuoteRequestAttributesExpanderPluginInterface;

/**
 * @method \Spryker\Glue\ProductOptionsRestApi\ProductOptionsRestApiFactory getFactory()
 */
class ProductOptionsRestQuoteRequestAttributesExpanderPlugin extends AbstractPlugin implements RestQuoteRequestAttributesExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands RestQuoteRequestItemTransfer with product options data.
     * - Translates product option's group name and value.
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
            ->expandRestQuoteRequestItemWithProductOptions(
                $restQuoteRequestsAttributesTransfers,
                $quoteRequestTransfers,
                $localeName
            );
    }
}
