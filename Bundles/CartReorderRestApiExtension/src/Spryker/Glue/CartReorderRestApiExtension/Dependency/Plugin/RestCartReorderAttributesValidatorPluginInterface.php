<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartReorderRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer;

/**
 * Use this plugin interface to extend validation of `RestCartReorderRequestAttributesTransfer`.
 */
interface RestCartReorderAttributesValidatorPluginInterface
{
    /**
     * Specification:
     * - Validates `RestCartReorderRequestAttributesTransfer`.
     * - Adds `RestErrorMessageTransfer` to `$restErrorMessageTransfers` array if request is not valid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer
     * @param list<\Generated\Shared\Transfer\RestErrorMessageTransfer> $restErrorMessageTransfers
     *
     * @return list<\Generated\Shared\Transfer\RestErrorMessageTransfer>
     */
    public function validate(RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer, array $restErrorMessageTransfers): array;
}
