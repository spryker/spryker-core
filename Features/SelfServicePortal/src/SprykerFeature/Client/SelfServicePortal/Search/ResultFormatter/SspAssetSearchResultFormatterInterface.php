<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeature\Client\SelfServicePortal\Search\ResultFormatter;

use Generated\Shared\Transfer\SspAssetSearchCollectionTransfer;

interface SspAssetSearchResultFormatterInterface
{
    /**
     * @param \Elastica\ResultSet|mixed $searchResult
     * @param array<string, mixed> $requestParameters
     *
     * @return \Generated\Shared\Transfer\SspAssetSearchCollectionTransfer
     */
    public function formatResult($searchResult, array $requestParameters = []): SspAssetSearchCollectionTransfer;
}
