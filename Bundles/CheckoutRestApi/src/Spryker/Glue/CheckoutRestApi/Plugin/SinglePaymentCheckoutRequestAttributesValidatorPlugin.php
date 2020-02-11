<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Plugin;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestAttributesValidatorPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\CheckoutRestApi\CheckoutRestApiFactory getFactory()
 * @method \Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig getConfig()
 */
class SinglePaymentCheckoutRequestAttributesValidatorPlugin extends AbstractPlugin implements CheckoutRequestAttributesValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks that only one payment method is set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validateAttributes(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): RestErrorCollectionTransfer
    {
        return $this->getFactory()
                ->createSinglePaymentValidator()
                ->validate($restCheckoutRequestAttributesTransfer);
    }
}
