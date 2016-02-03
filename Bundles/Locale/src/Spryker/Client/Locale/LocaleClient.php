<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Locale;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Shared\Kernel\Store;

/**
 * @method \Spryker\Client\Locale\LocaleFactory getFactory()
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
