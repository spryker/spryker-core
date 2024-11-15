<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartReorderRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer;

/**
 * Use this plugin interface to map `RestCartReorderRequestAttributesTransfer` to `CartReorderRequestTransfer`.
 */
interface RestCartReorderAttributesMapperPluginInterface
{
    /**
     * Specification:
     * - Maps data from `RestCartReorderRequestAttributesTransfer` to `CartReorderRequestTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderRequestTransfer
     */
    public function mapRestCartReorderRequestAttributesToCartReorderRequestTransfer(
        RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer,
        CartReorderRequestTransfer $cartReorderRequestTransfer
    ): CartReorderRequestTransfer;
}
