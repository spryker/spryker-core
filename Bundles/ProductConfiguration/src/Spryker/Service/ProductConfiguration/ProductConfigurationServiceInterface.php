<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductConfiguration;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

interface ProductConfigurationServiceInterface
{
    /**
     * Specification
     * - Generates a hash for ProductConfigurationInstanceTransfer.
     * - Uses md5 as hashing algorithm.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return string
     */
    public function getProductConfigurationInstanceHash(ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer): string;
}
