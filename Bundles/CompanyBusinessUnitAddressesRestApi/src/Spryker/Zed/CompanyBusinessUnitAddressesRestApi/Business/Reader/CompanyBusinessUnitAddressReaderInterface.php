<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Reader;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestAddressTransfer;

interface CompanyBusinessUnitAddressReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestAddressTransfer $restAddressTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function getCompanyBusinessUnitAddress(
        RestAddressTransfer $restAddressTransfer,
        QuoteTransfer $quoteTransfer
    ): AddressTransfer;
}
