<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;

interface CartItemsNormalizerPluginInterface
{
    /**
     * Specification:
     * - Checks if normalizer is applicable for the given cart change transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return bool
     */
    public function isApplicable($cartChangeTransfer): bool;

    /**
     * Specification:
     * - Executes cart change items normalization before preCheckCart checks.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function normalizeItems($cartChangeTransfer): CartChangeTransfer;
}
