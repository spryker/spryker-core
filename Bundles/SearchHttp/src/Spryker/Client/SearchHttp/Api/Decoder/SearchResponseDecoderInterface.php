<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api\Decoder;

use Generated\Shared\Transfer\AcpHttpResponseTransfer;

interface SearchResponseDecoderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AcpHttpResponseTransfer $acpHttpResponseTransfer
     *
     * @return array<string, mixed>
     */
    public function decode(AcpHttpResponseTransfer $acpHttpResponseTransfer): array;
}
