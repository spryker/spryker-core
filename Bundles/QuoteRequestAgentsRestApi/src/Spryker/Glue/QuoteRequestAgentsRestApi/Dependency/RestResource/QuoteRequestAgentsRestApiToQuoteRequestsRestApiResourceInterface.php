<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\RestResource;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface QuoteRequestAgentsRestApiToQuoteRequestsRestApiResourceInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createQuoteRequestRestResponse(
        QuoteRequestResponseTransfer $quoteRequestResponseTransfer,
        string $localeName
    ): RestResponseInterface;
}
