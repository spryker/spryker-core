<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImage;

interface ProductImageClientInterface
{
    /**
     * Specification:
     * - Checks if image alternative text feature is enabled.
     * - Gets the value from module configuration.
     *
     * @api
     *
     * @deprecated This method will be removed in the next major version. Product image alternative text will be enabled by default.
     *
     * @return bool
     */
    public function isProductImageAlternativeTextEnabled(): bool;
}
