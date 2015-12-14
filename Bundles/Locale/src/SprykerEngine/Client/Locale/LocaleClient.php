<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Client\Locale;

use SprykerEngine\Client\Kernel\AbstractClient;
use SprykerEngine\Shared\Kernel\Store;

/**
 * @method LocaleDependencyContainer getDependencyContainer()
 */
class LocaleClient extends AbstractClient implements LocaleClientInterface
{

    /**
     * @return string
     */
    public function getCurrentLocale()
    {
        return Store::getInstance()->getCurrentLocale();
    }

}
