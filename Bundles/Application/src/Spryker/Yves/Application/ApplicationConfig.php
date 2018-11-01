<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Application;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class ApplicationConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getHostName()
    {
        return $this->get(ApplicationConstants::HOST_YVES);
    }

    /**
     * @return bool
     */
    public function isSslEnabled()
    {
        return $this->get(ApplicationConstants::YVES_SSL_ENABLED, true);
    }

    /**
     * @return array
     */
    public function getSslExcludedResources()
    {
        return $this->get(ApplicationConstants::YVES_SSL_EXCLUDED, []);
    }

    /**
     * @return array
     */
    public function getTrustedProxies()
    {
        return $this->get(ApplicationConstants::YVES_TRUSTED_PROXIES, []);
    }

    /**
     * @return array
     */
    public function getTrustedHosts()
    {
        return $this->get(ApplicationConstants::YVES_TRUSTED_HOSTS, []);
    }
}
