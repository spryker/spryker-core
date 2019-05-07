<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;

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
    public function getQuoteByResourceShareUuid(string $resourceShareUuid): QuoteResponseTransfer;

    /**
     * Specification:
     * - Retrieves cart share options from CartShareOptionPluginInterface plugins.
     *
     * @api
     *
     * @return string[][]
     */
    public function getCartShareOptions(): array;

    /**
     * Specification:
     * - Generates share resource for the provided cart and current user and provided share option.
     * - Sets UUID in returned transfer if generation was successful.
     * - Sets `isSuccessful=true` if generation was successful, adds error messages otherwise.
     *
     * @api
     *
     * @param int $idQuote
     * @param string $shareOption
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function generateCartResourceShare(int $idQuote, string $shareOption): ResourceShareResponseTransfer;
}
