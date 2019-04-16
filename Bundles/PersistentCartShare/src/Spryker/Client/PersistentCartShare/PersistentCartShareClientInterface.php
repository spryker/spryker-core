<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare;

use Generated\Shared\Transfer\ResourceShareResponseTransfer;

interface PersistentCartShareClientInterface
{
    /**
     * Specification:
     * - Retrieves cart share options from CartShareOptionPluginInterface plugins.
     *
     * @api
     *
     * @return string[]
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
