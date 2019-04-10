<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare;

use Generated\Shared\Transfer\QuoteResponseTransfer;

interface PersistentCartShareClientInterface
{
    /**
     * Specification:
     * - Makes Zed-Request.
     * - Retrieves a quote based on the provided UUID.
     * - Validates if provided UUID refers to a "preview" type cart share.
     * - Returns "isSuccess=true" on success and error message otherwise.
     *
     * @api
     *
     * @param string $resourceShareUuid
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function getQuoteForPreview(string $resourceShareUuid): QuoteResponseTransfer;
}
