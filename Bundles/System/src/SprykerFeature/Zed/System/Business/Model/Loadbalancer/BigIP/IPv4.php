<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\System\Business\Model\Loadbalancer\BigIP;

use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Shared\Library\Environment;

class IPv4
{

    /**
     * string
     */
    const APPLICATION_NAME_ZED = 'zed';

    /**
     * string
     */
    const APPLICATION_NAME_YVES = 'yves';

    /**
     * @var string
     */
    protected $environment;

    /**
     * @param null $environment
     */
    public function __construct($environment = null)
    {
        if ($environment === null) {
            $this->environment = Environment::isProduction() ? 'production' : 'staging';
        }
        $this->environment = $environment;
    }

    /**
     * @param string $ipAddress
     * @param string $portNumber
     *
     * @return string
     */
    public function calculateStickyCookieValue($ipAddress, $portNumber)
    {
        return $this->getStringByIpAddress($ipAddress) . '.' . $this->getStringByPortNumber($portNumber) . '.' . $this->factory->createSettings()->getLoadbalancerPostfixString();
    }

    /**
     * @param string $ipAddress
     *
     * @return number
     */
    protected function getStringByIpAddress($ipAddress)
    {
        $reversedIpParts = array_reverse(explode('.', $ipAddress));
        $code = '';

        foreach ($reversedIpParts as $ipPart) {
            $decHex = dechex($ipPart);
            $code .= ((strlen($decHex) === 1) ? '0' : '') . $decHex;
        }

        return hexdec($code);
    }

    /**
     * @param string $portNumber
     *
     * @return number
     */
    protected function getStringByPortNumber($portNumber)
    {
        $code = '';
        if ((int) $portNumber < 256) {
            $code = '00';
        }

        $code .= dechex($portNumber);

        $firstByte = substr($code, 0, 2);
        $secondByte = substr($code, 2, 2);
        $reversedString = $secondByte . $firstByte;

        return hexdec($reversedString);
    }

    /**
     * @param string $hostname
     * @param string $applicationName
     *
     * @throws \ErrorException
     *
     * @return string
     */
    public function getCookieValueByHost($hostname, $applicationName)
    {
        if ($applicationName !== self::APPLICATION_NAME_ZED && $applicationName !== self::APPLICATION_NAME_YVES) {
            throw new \ErrorException('Cannot find loadbalancer setting for application ' . $applicationName);
        }

        $hosts = $this->factory->createSettings()->getHosts($this->environment);

        foreach ($hosts as $host) {
            if ($host['key_host'] === $hostname) {
                $ipAddress = $this->factory->createSettings()->getHostIpAddressByHostname($hostname);

                if ($applicationName === self::APPLICATION_NAME_ZED) {
                    return $this->calculateStickyCookieValue($ipAddress, $host['key_zed_port']);
                } else {
                    return $this->calculateStickyCookieValue($ipAddress, $host['yves_port']);
                }
            }
        }

        throw new \ErrorException('Could not find configuration for hostname ' . $hostname);
    }

    /**
     * @param $applicationName
     * @param null $store
     *
     * @return string
     */
    public function getCookieName($applicationName, $store = null)
    {
        if (!$store) {
            $store = Store::getInstance()->getStoreName();
        }

        $poolNumber = $this->factory->createSettings()->getStorePoolNumberByStore($store);

        if ($this->environment === 'staging') {
            return 'BIGipServerpool_' . $poolNumber . '_' . $this->environment;
        } else {
            return 'BIGipServerpool_' . $poolNumber . '_' . $this->environment . '_' . $applicationName;
        }
    }

}
