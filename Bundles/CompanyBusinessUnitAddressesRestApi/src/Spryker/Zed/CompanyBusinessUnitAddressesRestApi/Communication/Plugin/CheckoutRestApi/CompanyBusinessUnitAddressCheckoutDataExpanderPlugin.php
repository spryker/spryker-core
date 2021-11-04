<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Communication\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiConfig getConfig()
 * @method \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\CompanyBusinessUnitAddressesRestApiFacadeInterface getFacade()
 */
class CompanyBusinessUnitAddressCheckoutDataExpanderPlugin extends AbstractPlugin implements CheckoutDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `RestCheckoutDataTransfer` with `CompanyBusinessUnitAddresses`.
     * - Requires `RestCheckoutRequestAttributesTransfer.Customer.idCompanyBusinessUnit` to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataTransfer
     */
    public function expandCheckoutData(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestCheckoutDataTransfer {
        return $this->getFacade()->expandCheckoutDataWithCompanyBusinessUnitAddresses(
            $restCheckoutDataTransfer,
            $restCheckoutRequestAttributesTransfer,
        );
    }
}
