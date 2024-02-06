<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantAgent;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class AclMerchantAgentConfig extends AbstractBundleConfig
{
    /**
     * @var list<string>
     */
    protected const MERCHANT_AGENT_ACL_BUNDLE_ALLOWED_LIST = [];

    /**
     * Specification:
     * - Retrieves a collection of bundles which merchant agent has ACL access to.
     *
     * @api
     *
     * @return list<string>
     */
    public function getMerchantAgentAclBundleAllowedList(): array
    {
        return static::MERCHANT_AGENT_ACL_BUNDLE_ALLOWED_LIST;
    }
}
