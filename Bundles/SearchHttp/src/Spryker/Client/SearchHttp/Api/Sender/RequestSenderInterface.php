<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api\Sender;

use Generated\Shared\Transfer\SearchHttpConfigTransfer;
use Psr\Http\Message\ResponseInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

interface RequestSenderInterface
{
    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send(QueryInterface $searchQuery, SearchHttpConfigTransfer $searchHttpConfigTransfer): ResponseInterface;
}
