<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api\Mapper;

use Generated\Shared\Transfer\SearchHttpResponseTransfer;
use Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer;

interface SearchHttpResponseTransferMapperInterface
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
    ): SearchHttpResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer $suggestionsSearchHttpResponseTransfer
     * @param array<string, mixed> $responseData
     *
     * @return \Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer
     */
    public function mapResponseDataToSuggestionsSearchHttpResponseTransfer(
        SuggestionsSearchHttpResponseTransfer $suggestionsSearchHttpResponseTransfer,
        array $responseData
    ): SuggestionsSearchHttpResponseTransfer;
}
