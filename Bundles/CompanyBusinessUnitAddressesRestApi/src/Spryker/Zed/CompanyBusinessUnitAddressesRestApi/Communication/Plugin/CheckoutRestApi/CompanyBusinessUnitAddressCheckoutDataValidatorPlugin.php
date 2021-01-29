<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Communication\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataValidatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\CompanyBusinessUnitAddressesRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiConfig getConfig()
 */
class CompanyBusinessUnitAddressCheckoutDataValidatorPlugin extends AbstractPlugin implements CheckoutDataValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `checkoutDataTransfer.quote.customer.companyUserTransfer.fkCompany` to be provided.
     * - Collects shipping address uuids from `checkoutDataTransfer.shipments`.
     * - Returns CheckoutResponseTransfer with error when company address uuid was provided for non-company user.
     * - Checks if company addresses exists.
     * - Returns CheckoutResponseTransfer with error if any check was failed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer
    {
        return $this->getFacade()->validateCompanyBusinessUnitAddressesInCheckoutData($checkoutDataTransfer);
    }
}
