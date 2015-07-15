<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

/**
 * @deprecated
 *
 * @todo get rid of me
 */

namespace SprykerFeature\Zed\System;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class SystemConfig extends AbstractBundleConfig
{

    const KEY_HOST = 'host';

    const KEY_ZED_PORT = 'zed_port';

    const KEY_YVES_PORT = 'yves_port';

    const APP01 = 'app01';

    const APP02 = 'app02';

    const APP03 = 'app03';

    const ADMIN01 = 'admin01';

    /**
     * @var array
     */
    protected $hostToIpAddressMapping = [];

    /**
     * @var array
     */
    public $storePoolMapping = [
        'DE' => '00',
        'PL' => '01',
        'FR' => '02',
        'AT' => '03',
        'NL' => '04',
        'CH' => '05',
        'BR' => '06',
        'UK' => '07',
        'BE' => '09',
        'US' => '10',
        'MX' => '11',
        'AR' => '12',
        'CL' => '13',
        'CO' => '14',
    ];

    /**
     * @return string
     */
    public function getLoadbalancerPostfixString()
    {
        return '0000';
    }

    /**
     * @return array
     */
    public function getHosts()
    {
        return [];
    }

    /**
     * @param string $hostname
     *
     * @return string
     */
    public function getHostIpAddressByHostname($hostname)
    {
        return $this->hostToIpAddressMapping[$hostname];
    }

    /**
     * @param string $store
     *
     * @return string
     */
    public function getStorePoolNumberByStore($store)
    {
        return $this->storePoolMapping[$store];
    }

    // FOR WATCHDOG
    /**
     * @return int
     */
    public function orderCheckShouldBePerformed()
    {
        $date = Zend_Date::now();
        $hour = (int) ($date->get('HH'));

        // there is no minimum amount for orders between 22h - 6h
        return ($hour > 6 && $hour < 22);
    }

    /**
     * @return array
     */
    public function getThresholds()
    {
        // minutes => amount of orders
        return [
            15 => 1,
        ];
    }

    /**
     * @return array
     */
    public function getRegisteredWatchdogChecks()
    {
        $checks = [];

        foreach ($this->getThresholds() as $minutes => $amountOfOrders) {
            $checks[] = $this->factory->createModelWatchdogSalesOrder($minutes, $amountOfOrders);
        }

        return $checks;
    }

    /**
     * @return array
     */
    public function getNotificationEmailGroups()
    {
        return [
            '',
        ];
    }

    /**
     * @return array
     */
    public function getNotificationEmailSettings()
    {
        return [
            \SprykerFeature_Zed_System_Business_Model_Watchdog_Abstract::NOTIFICATION_FROM => '',
            \SprykerFeature_Zed_System_Business_Model_Watchdog_Abstract::NOTIFICATION_SUBJECT => '',
        ];
    }

    /**
     * @return string
     */
    public function getPathToPhpErrorsLog()
    {
        return '/data/logs/php_errors.log';
    }

}
