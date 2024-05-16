<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp;

use Spryker\Shared\TaxApp\TaxAppConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class TaxAppConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getOauthProviderNameForTaxCalculation(): string
    {
        return $this->get(TaxAppConstants::OAUTH_PROVIDER_NAME, '');
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOauthGrantTypeForTaxCalculation(): string
    {
        return $this->get(TaxAppConstants::OAUTH_GRANT_TYPE, '');
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOauthOptionAudienceForTaxCalculation(): string
    {
        return $this->get(TaxAppConstants::OAUTH_OPTION_AUDIENCE, '');
    }

    /**
     * Specification:
     * - 2 letters ISO country code, for example US, DE
     * - Overrides the default value (the first country of the store defined in the Quote/Order).
     *
     * @api
     *
     * @return string
     */
    public function getSellerCountryCode(): string
    {
        return '';
    }

    /**
     * Specification:
     * - 2 letters ISO country code, for example US, DE
     * - Used for tax calculation when a customer did not provide shipping address.
     * - Overrides the default value (the first country of the store defined in the Quote/Order).
     *
     * @api
     *
     * @return string
     */
    public function getCustomerCountryCode(): string
    {
        return '';
    }
}
