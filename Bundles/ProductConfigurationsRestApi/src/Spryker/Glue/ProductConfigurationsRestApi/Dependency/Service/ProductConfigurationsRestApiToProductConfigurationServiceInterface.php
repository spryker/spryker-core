<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi\Dependency\Service;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

interface ProductConfigurationsRestApiToProductConfigurationServiceInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return string
     */
    public function getProductConfigurationInstanceHash(ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer): string;
}
