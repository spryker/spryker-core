<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api\Decoder;

use Psr\Http\Message\ResponseInterface;

interface SearchResponseDecoderInterface
{
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return array<string, mixed>
     */
    public function decode(ResponseInterface $response): array;
}
