<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApiExtension\Dependency\Plugin;

/**
 * Provides extension capabilities for quote request REST API attributes.
 *
 * Allows expanding REST API quote request attributes with additional data. The original `QuoteRequestTransfer` collection is provided as an immutable parameter and can be used for expansion.
 */
interface RestQuoteRequestAttributesExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands `RestQuoteRequestsAttributesTransfers` with additional data.
     * - `QuoteRequestTransfer` collection is immutable and should not be changed by the plugins.
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
    ): array;
}
