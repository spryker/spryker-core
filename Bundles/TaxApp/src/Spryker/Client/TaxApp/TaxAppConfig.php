<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\TaxApp;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\TaxApp\TaxAppConstants;

class TaxAppConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const REQUEST_TIMEOUT_SECONDS = 5;

    /**
     * @api
     *
     * @return int
     */
    public function getRequestTimeoutInSeconds(): int
    {
        return static::REQUEST_TIMEOUT_SECONDS;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTenantIdentifier(): string
    {
        return $this->get(TaxAppConstants::TENANT_IDENTIFIER, '');
    }
}
