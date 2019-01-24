<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncoding\FormatDecoder;

class QueryStringFormatDecoder implements FormatDecoderInterface
{
    /**
     * @return string
     */
    public function getFormatName(): string
    {
        return 'querystring';
    }

    /**
     * @param string $data
     *
     * @return array
     */
    public function decode(string $data): array
    {
        $result = [];
        parse_str($data, $result);

        return $result;
    }
}
