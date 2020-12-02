<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestValidatorPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiFactory getFactory()
 */
class AddressSourceCheckoutRequestValidatorPlugin extends AbstractPlugin implements CheckoutRequestValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Executes `AddressSourceCheckerPluginInterface` plugin stack.
     * - Validates the given shipments attributes and returns an array of errors if necessary.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validateAttributes(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestErrorCollectionTransfer {
        return $this->getFactory()
            ->createAddressSourceCheckoutDataValidator()
            ->validateAttributes($restCheckoutRequestAttributesTransfer);
    }
}
