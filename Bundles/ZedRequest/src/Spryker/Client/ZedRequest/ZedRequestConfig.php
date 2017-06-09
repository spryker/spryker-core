<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\ZedRequest\ZedRequestConstants;

class ZedRequestConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getRawToken()
    {
        $authConfig = $this->getConfig()->get(ZedRequestConstants::AUTH_DEFAULT_CREDENTIALS);
        $rawToken = $authConfig['yves_system']['token'];

        return $rawToken;
    }

    /**
     * @return int
     */
    public function getHashCost()
    {
        if (!$this->getConfig()->hasKey(ZedRequestConstants::AUTH_HASH_COST)) {
           return ZedRequestConstants::DEFAULT_AUTH_HASH_COST;
        }

        return $this->get(ZedRequestConstants::AUTH_HASH_COST);
    }

    /**
     * @return string
     */
    public function getZedRequestBaseUrl()
    {
        $sslEnabled = $this->getConfig()->get(ZedRequestConstants::ZED_API_SSL_ENABLED);

        if ($sslEnabled === true) {
            return 'https://' . $this->getConfig()->get(ZedRequestConstants::HOST_SSL_ZED_API);
        } else {
            return 'http://' . $this->getConfig()->get(ZedRequestConstants::HOST_ZED_API);
        }
    }

    /**
     * @return bool
     */
    public function isAuthenticationEnabled()
    {
        return $this->getConfig()->get(ZedRequestConstants::AUTH_ZED_ENABLED, true);
    }

}
