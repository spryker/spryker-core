<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestAttributesValidatorPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\CustomersRestApi\CustomersRestApiFactory getFactory()
 */
class BillingAddressCheckoutRequestAttributesValidatorPlugin extends AbstractPlugin implements CheckoutRequestAttributesValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Ignores validation if `RestCheckoutRequestAttributesTransfer.billingAddress` is not provided.
     * - Skips validation if `RestCheckoutRequestAttributesTransfer.billingAddress` contains fields from `CustomersRestApiConfig::getBillingAddressFieldsToSkipValidation()`.
     * - Validates `RestCheckoutRequestAttributesTransfer.billingAddress` mandatory fields.
     * - Returns `RestErrorCollectionTransfer` with errors if validation failed.
     * - Returns empty `RestErrorCollectionTransfer` if validation passed.
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
            ->createCheckoutBillingAddressChecker()
            ->checkMandatoryFields($restCheckoutRequestAttributesTransfer);
    }
}
