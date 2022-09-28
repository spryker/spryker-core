<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Communication\Plugin\TaxProductConnector;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\TaxProductConnectorExtension\Communication\Dependency\Plugin\ShippingAddressValidatorPluginInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 *
 * @method \Spryker\Zed\CompanyUnitAddress\CompanyUnitAddressConfig getConfig()
 * @method \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressQueryContainerInterface getQueryContainer()
 */
class CompanyUnitAddressShippingAddressValidatorPlugin extends AbstractPlugin implements ShippingAddressValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if `AddressTransfer.idCompanyUnitAddress` is set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return bool
     */
    public function isValid(AddressTransfer $addressTransfer): bool
    {
        return $addressTransfer->getIdCompanyUnitAddress() !== null;
    }
}
