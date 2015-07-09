<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\System\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

class SystemFacade extends AbstractFacade
{

    /**
     * @param string $environment
     * @param string $ipAddress
     * @param string $portNumber
     *
     * @return string
     */
    public function getBigIpCookieContent($environment, $ipAddress, $portNumber)
    {
        return $this->factory->createModelLoadbalancerBigIPIPv4($environment)->calculateStickyCookieValue($ipAddress, $portNumber);
    }

    /**
     * @param string $environment
     * @param string $hostname
     * @param string $applicationName
     *
     * @return string
     */
    public function getCookieValueByHost($environment, $hostname, $applicationName)
    {
        return $this->factory->createModelLoadbalancerBigIPIPv4($environment)->getCookieValueByHost($hostname, $applicationName);
    }

    /**
     * @param string $environment
     *
     * @return array
     */
    public function getHosts($environment)
    {
        return $this->factory->createSettings()->getHosts($environment);
    }

    /**
     * @param string $environment
     * @param string $applicationName
     * @param null $store
     *
     * @return string
     */
    public function getCookieName($environment, $applicationName, $store = null)
    {
        return $this->factory->createModelLoadbalancerBigIPIPv4($environment)->getCookieName($applicationName, $store);
    }

}
