<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Plugin\QuoteRequestsRestApi;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\QuoteRequestsRestApiExtension\Dependency\Plugin\RestQuoteRequestAttributesExpanderPluginInterface;

/**
 * @method \Spryker\Glue\ProductMeasurementUnitsRestApi\ProductMeasurementUnitsRestApiFactory getFactory()
 */
class SalesUnitRestQuoteRequestAttributesExpanderPlugin extends AbstractPlugin implements RestQuoteRequestAttributesExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands RestQuoteRequestItemTransfers with sales unit data.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer> $restQuoteRequestsAttributesTransfers
     * @param array<\Generated\Shared\Transfer\QuoteRequestTransfer> $quoteRequestTransfers
     * @param string $localeName
     *
     * @return array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer>
     */
    public function expand(
        array $restQuoteRequestsAttributesTransfers,
        array $quoteRequestTransfers,
        string $localeName
    ): array {
        return $this->getFactory()
            ->createQuoteRequestItemExpander()
            ->expandRestQuoteRequestItemWithSalesUnit(
                $restQuoteRequestsAttributesTransfers,
                $quoteRequestTransfers,
                $localeName
            );
    }
}
