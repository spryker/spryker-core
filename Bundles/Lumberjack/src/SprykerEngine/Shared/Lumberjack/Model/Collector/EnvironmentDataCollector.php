<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model\Collector;

use SprykerEngine\Shared\Kernel\Store;

class EnvironmentDataCollector extends AbstractDataCollector
{

    /**
     * @return array
     */
    public function getData()
    {
        return [
            'app'          => APPLICATION,
            'app_env'      => APPLICATION_ENV,
            'date_time'    => gmdate('Y-m-d H:i:s'),
            'app_store'    => Store::getInstance()->getStoreName(),
            'app_language' => Store::getInstance()->getCurrentLanguage(),
            'app_locale'   => Store::getInstance()->getCurrentLocale(),
            'app_currency' => Store::getInstance()->getCurrencyIsoCode(),
        ];
    }

}
