<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestAttributesValidatorPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiFactory getFactory()
 */
class ServicePointCheckoutRequestAttributesValidatorPlugin extends AbstractPlugin implements CheckoutRequestAttributesValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `RestCheckoutRequestAttributesTransfer.servicePoints.idServicePoint` to be set.
     * - Returns empty `RestErrorCollectionTransfer` if `RestCheckoutRequestAttributesTransfer.servicePoints` is empty.
     * - Checks that service point items are unique.
     * - Checks that service points are active and available for the current store.
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
            ->createServicePointCheckoutRequestAttributesValidator()
            ->validate($restCheckoutRequestAttributesTransfer);
    }
}
