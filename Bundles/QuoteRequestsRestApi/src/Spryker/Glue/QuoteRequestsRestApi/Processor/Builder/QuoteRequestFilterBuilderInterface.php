<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Builder;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface QuoteRequestFilterBuilderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param bool $isParent
     *
     * @return \Generated\Shared\Transfer\QuoteRequestFilterTransfer|null
     */
    public function buildFilterFromRequest(RestRequestInterface $restRequest, bool $isParent = false): ?QuoteRequestFilterTransfer;
}
