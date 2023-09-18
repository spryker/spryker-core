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
}
