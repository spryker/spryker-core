<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Communication\Plugin\ShipmentsRestApi;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestAddressTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShipmentsRestApiExtension\Dependency\Plugin\AddressProviderStrategyPluginInterface;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiConfig getConfig()
 * @method \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\CompanyBusinessUnitAddressesRestApiFacadeInterface getFacade()
 */
class CompanyBusinessUnitAddressProviderStrategyPlugin extends AbstractPlugin implements AddressProviderStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if `RestAddressTransfer.idCompanyBusinessUnitAddress` is provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestAddressTransfer $restAddressTransfer
     *
     * @return bool
     */
    public function isApplicable(RestAddressTransfer $restAddressTransfer): bool
    {
        return $restAddressTransfer->getIdCompanyBusinessUnitAddress() !== null;
    }

    /**
     * {@inheritDoc}
     * - Finds company business unit address based on UUID provided in `RestAddressTransfer.idCompanyBusinessUnitAddress`.
     * - Returns `AddressTransfer` filled with company business unit address information if it was found.
     * - If company business unit address was found then address information provided in `RestAddressTransfer` will be skipped.
     * - Returns `AddressTransfer` filled with attributes from `RestAddressTransfer` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestAddressTransfer $restAddressTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function provideAddress(
        RestAddressTransfer $restAddressTransfer,
        QuoteTransfer $quoteTransfer
    ): AddressTransfer {
        return $this->getFacade()->getCompanyBusinessUnitAddress($restAddressTransfer, $quoteTransfer);
    }
}
