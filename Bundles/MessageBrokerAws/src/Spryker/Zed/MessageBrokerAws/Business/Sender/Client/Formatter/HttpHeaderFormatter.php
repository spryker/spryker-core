<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Formatter;

use Generated\Shared\Transfer\MessageAttributesTransfer;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig;

class HttpHeaderFormatter implements HttpHeaderFormatterInterface
{
    /**
     * @var string
     */
    protected const HEADER_NAME_PREFIX = 'X-';

    /**
     * @var \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig
     */
    protected $messageBrokerAwsConfig;

    /**
     * @param \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig $messageBrokerAwsConfig
     */
    public function __construct(MessageBrokerAwsConfig $messageBrokerAwsConfig)
    {
        $this->messageBrokerAwsConfig = $messageBrokerAwsConfig;
    }

    /**
     * @param array<string, mixed> $headers
     *
     * @return array<string, mixed>
     */
    public function formatHeaders(array $headers): array
    {
        $formattedHeaders = [];

        /** @var array<int, string> $standardHttpHeaders */
        $standardHttpHeaders = array_map('mb_strtolower', $this->messageBrokerAwsConfig->getStandardHttpHeaders());
        $allowedHeaders = $this->getAllowedHeaders();

        foreach ($headers as $header => $value) {
            $headerName = $this->prepareHeader($standardHttpHeaders, $allowedHeaders, $header);
            if (!$headerName) {
                continue;
            }

            if (is_array($value)) {
                $value = json_encode($value);
            }

            $formattedHeaders[$headerName] = $value;
        }

        return $formattedHeaders;
    }

    /**
     * @return array<int, string>
     */
    protected function getAllowedHeaders(): array
    {
        $allowedHeaders = (new MessageAttributesTransfer())->toArrayNotRecursiveCamelCased();
        $allowedHeaders['publisher'] = null;

        return array_keys($allowedHeaders);
    }

    /**
     * @param array<int, string> $standardHttpHeaders
     * @param array<int, string> $allowedHeaders
     * @param string $header
     *
     * @return string|null
     */
    protected function prepareHeader(array $standardHttpHeaders, array $allowedHeaders, string $header): ?string
    {
        if (!in_array($header, $allowedHeaders, true)) {
            return null;
        }

        $header = preg_replace('/(?<=[a-z])(?=[A-Z])/', '-', ucfirst($header));
        if (!$header) {
            return null;
        }

        $lowerCasedHeader = mb_strtolower($header);

        if (in_array($lowerCasedHeader, $standardHttpHeaders, true)) {
            return $header;
        }

        return sprintf('%s%s', static::HEADER_NAME_PREFIX, $header);
    }
}
