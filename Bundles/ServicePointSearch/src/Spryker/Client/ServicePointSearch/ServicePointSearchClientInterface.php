<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointSearch;

use Generated\Shared\Transfer\ServicePointSearchRequestTransfer;

interface ServicePointSearchClientInterface
{
    /**
     * Specification:
     * - Filters Elasticsearch records using criteria from `ServicePointSearchRequestTransfer`.
     * - Returns search results.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ServicePointSearchRequestTransfer $servicePointSearchRequestTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ServicePointSearchCollectionTransfer>
     */
    public function searchServicePoints(ServicePointSearchRequestTransfer $servicePointSearchRequestTransfer): array;
}
