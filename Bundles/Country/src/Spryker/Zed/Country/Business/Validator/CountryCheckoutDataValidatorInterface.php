<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Validator;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;

interface CountryCheckoutDataValidatorInterface
{
    /**
     * @deprecated Use {@link \Spryker\Zed\Country\Business\Validator\CountryCheckoutDataValidatorInterface::validateCountriesInCheckoutData()} instead.
     *
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateCountryCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateCountriesInCheckoutData(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer;
}
