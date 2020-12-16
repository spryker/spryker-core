<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SecurityBlockerRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class SecurityBlockerRestApiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Client\SecurityBlocker\SecurityBlockerConfig::SECURITY_BLOCKER_CUSTOMER_ENTITY_TYPE
     */
    public const SECURITY_BLOCKER_CUSTOMER_ENTITY_TYPE = 'customer';

    /**
     * @uses \Spryker\Client\SecurityBlocker\SecurityBlockerConfig::SECURITY_BLOCKER_CUSTOMER_ENTITY_TYPE
     */
    public const SECURITY_BLOCKER_AGENT_ENTITY_TYPE = 'agent';

    public const ERROR_RESPONSE_CODE_ACCOUNT_BLOCKED = '4401';
    public const ERROR_RESPONSE_DETAIL_ACCOUNT_BLOCKED = 'Too many log in attempts from your address. Please wait %s minutes before trying again.';
}
