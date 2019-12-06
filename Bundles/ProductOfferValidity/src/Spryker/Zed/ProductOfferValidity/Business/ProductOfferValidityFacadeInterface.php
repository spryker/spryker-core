<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidity\Business;

interface ProductOfferValidityFacadeInterface
{
    /**
     * Specification:
     * - Finds product offers that are about to become valid/invalid for the current time.
     * - Product offers that are about to become active will be updated to 'is_active' = true in the database.
     * - Product offers that are about to become inactive will be updated to 'is_active' = false in the database.
     *
     * @api
     *
     * @return void
     */
    public function checkProductOfferValidityDateRange(): void;
}
