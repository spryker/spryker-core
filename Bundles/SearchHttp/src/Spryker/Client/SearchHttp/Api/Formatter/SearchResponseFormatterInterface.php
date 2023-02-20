<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api\Formatter;

use Psr\Http\Message\ResponseInterface;

interface SearchResponseFormatterInterface
{
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface> $resultFormatters
     * @param array<string, mixed> $requestParameters
     *
     * @return array<string, mixed>
     */
    public function format(
        ResponseInterface $response,
        array $resultFormatters = [],
        array $requestParameters = []
    ): array;
}
