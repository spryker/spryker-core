<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageStorage\Dependency\Client;

interface ProductImageStorageToProductImageClientInterface
{
    /**
     * @return bool
     */
    public function isProductImageAlternativeTextEnabled(): bool;
}
