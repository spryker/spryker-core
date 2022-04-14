<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Formatter;

use Generated\Shared\Transfer\MessageAttributesTransfer;

class HttpHeaderFormatter implements HttpHeaderFormatterInterface
{
    /**
     * @var string
     */
    protected const HEADER_NAME_SUFFIX = 'X-';

    /**
     * @param array<string, mixed> $headers
     *
     * @return array<string, mixed>
     */
    public function formatHeaders(array $headers): array
    {
        $formattedHeaders = [];
        foreach ($headers as $header => $value) {
            if (!property_exists(MessageAttributesTransfer::class, $header)) {
                continue;
            }

            $header = preg_replace('/(?<=[a-z])(?=[A-Z])/', '-', $header);

            if (!$header) {
                continue;
            }

            $headerName = sprintf('%s%s', static::HEADER_NAME_SUFFIX, ucfirst($header));

            if (is_array($value)) {
                $value = json_encode($value);
            }

            $formattedHeaders[$headerName] = $value;
        }

        return $formattedHeaders;
    }
}
