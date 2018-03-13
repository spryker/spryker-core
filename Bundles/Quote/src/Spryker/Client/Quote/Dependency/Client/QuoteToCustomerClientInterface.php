<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\Dependency\Client;

interface QuoteToCustomerClientInterface
{
    /**
     * Specification:
     * - Checks if customer information is present in session.
     *
     * @api
     *
     * @return bool
     */
    public function isLoggedIn();

    /**
     * Specification:
     * - Returns customer information from session.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomer();
}
