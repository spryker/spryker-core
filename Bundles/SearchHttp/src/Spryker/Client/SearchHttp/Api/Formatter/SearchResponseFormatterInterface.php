<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api\Formatter;

interface SearchResponseFormatterInterface
{
    /**
     * @param array<string, mixed> $responseData
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface> $resultFormatters
     * @param array<string, mixed> $requestParameters
     *
     * @return array<string, mixed>
     */
    public function format(
        array $responseData,
        array $resultFormatters = [],
        array $requestParameters = []
    ): array;

    /**
     * @param array<string, mixed> $responseData
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface> $resultFormatters
     * @param array<string, mixed> $requestParameters
     *
     * @return array<string, mixed>
     */
    public function formatSuggestionResponse(
        array $responseData,
        array $resultFormatters = [],
        array $requestParameters = []
    ): array;
}
