<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\RestProductPriceAttributesTransfer;

/**
 * Use this plugin to map additional data from `CurrentProductPriceTransfer` to `RestProductPricesAttributesTransfer`.
 */
interface RestProductPricesAttributesMapperPluginInterface
{
    /**
     * Specification:
     * - Maps the `CurrentProductPriceTransfer` to `RestProductPricesAttributesTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     * @param \Generated\Shared\Transfer\RestProductPriceAttributesTransfer $restProductPriceAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductPriceAttributesTransfer
     */
    public function map(
        CurrentProductPriceTransfer $currentProductPriceTransfer,
        RestProductPriceAttributesTransfer $restProductPriceAttributesTransfer
    ): RestProductPriceAttributesTransfer;
}
