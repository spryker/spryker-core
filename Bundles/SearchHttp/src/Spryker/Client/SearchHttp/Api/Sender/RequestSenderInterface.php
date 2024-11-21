<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api\Sender;

use Generated\Shared\Transfer\AcpHttpResponseTransfer;
use Generated\Shared\Transfer\SearchHttpConfigTransfer;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

interface RequestSenderInterface
{
    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpResponseTransfer
     */
    public function send(QueryInterface $searchQuery, SearchHttpConfigTransfer $searchHttpConfigTransfer): AcpHttpResponseTransfer;

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpResponseTransfer
     */
    public function sendSuggestionRequest(QueryInterface $searchQuery, SearchHttpConfigTransfer $searchHttpConfigTransfer): AcpHttpResponseTransfer;
}
