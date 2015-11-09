<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Client\Locale\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;
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
