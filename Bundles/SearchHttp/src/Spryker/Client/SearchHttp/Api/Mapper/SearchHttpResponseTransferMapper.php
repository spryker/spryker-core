<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api\Mapper;

use Generated\Shared\Transfer\SearchHttpResponseTransfer;

class SearchHttpResponseTransferMapper implements SearchHttpResponseTransferMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SearchHttpResponseTransfer $searchHttpResponseTransfer
     * @param array<string, mixed> $responseData
     *
     * @return \Generated\Shared\Transfer\SearchHttpResponseTransfer
     */
    public function mapResponseDataToSearchHttpResponseTransfer(
        SearchHttpResponseTransfer $searchHttpResponseTransfer,
        array $responseData
    ): SearchHttpResponseTransfer {
        return $searchHttpResponseTransfer->fromArray($responseData, true);
    }
}
