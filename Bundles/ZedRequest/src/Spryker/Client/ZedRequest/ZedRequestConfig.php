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
        return 4;
    }

    /**
     * @return string
     */
    public function getZedRequestBaseUrl()
    {
        $sslEnabled = $this->getConfig()->get(ZedRequestConstants::ZED_API_SSL_ENABLED);

        if ($sslEnabled === true) {
            // @deprecated This is just for backward compatibility
            if (!$this->getConfig()->hasKey(ZedRequestConstants::BASE_URL_SSL_ZED_API)) {
                return 'https://' . $this->getConfig()->get(ZedRequestConstants::HOST_SSL_ZED_API);
            }

            return $this->getConfig()->get(ZedRequestConstants::BASE_URL_SSL_ZED_API);
        } else {
            // @deprecated This is just for backward compatibility
            if (!$this->getConfig()->hasKey(ZedRequestConstants::BASE_URL_ZED_API)) {
                return 'http://' . $this->getConfig()->get(ZedRequestConstants::HOST_ZED_API);
            }

            return $this->getConfig()->get(ZedRequestConstants::BASE_URL_ZED_API);
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
