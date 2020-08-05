<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Business;

use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationFilterTransfer;

/**
 * @method \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedBusinessFactory getFactory()
 */
interface ProductConfigurationFacadeInterface
{
    /**
     * Specification:
     *  - Retrieves product configurations from Persistence.
     *  - Returns Product Configurations that mach given criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer
     */
    public function getProductConfigurationCollection(
        ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
    ): ProductConfigurationCollectionTransfer;
}
