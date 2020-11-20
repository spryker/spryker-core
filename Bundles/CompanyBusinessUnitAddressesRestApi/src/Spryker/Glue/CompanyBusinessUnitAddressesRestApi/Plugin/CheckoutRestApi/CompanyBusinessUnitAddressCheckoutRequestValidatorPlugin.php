<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestValidatorPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiFactory getFactory()
 */
class CompanyBusinessUnitAddressCheckoutRequestValidatorPlugin extends AbstractPlugin implements CheckoutRequestValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `restCheckoutRequestAttributes.restUser.idCompany` to be provided.
     * - Collects shipping address uuids from `restCheckoutRequestAttributes.shipments`.
     * - Returns CheckoutResponseTransfer with error when company address uuid was provided for non-company user.
     * - Checks if company addresses exists.
     * - Returns CheckoutResponseTransfer with error if any check was failed.
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
            ->createCompanyBusinessUnitAddressValidator()
            ->validateCompanyBusinessUnitAddresses($restCheckoutRequestAttributesTransfer);
    }
}
