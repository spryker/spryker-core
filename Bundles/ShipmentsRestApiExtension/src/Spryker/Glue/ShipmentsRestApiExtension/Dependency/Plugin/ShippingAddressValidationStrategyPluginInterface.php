<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;

/**
 * Validates shipping address for shipments inside `RestCheckoutRequestAttributesTransfer`.
 */
interface ShippingAddressValidationStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if this plugin is applicable for provided `RestCheckoutRequestAttributesTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return bool
     */
    public function isApplicable(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): bool;

    /**
     * Specification:
     * - Validates shipping addresses for `RestCheckoutRequestAttributesTransfer`.
     * - Returns a collection of validation errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validate(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestErrorCollectionTransfer;
}
