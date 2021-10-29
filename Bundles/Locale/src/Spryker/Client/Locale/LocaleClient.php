<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getCurrentLocale()
    {
        return Store::getInstance()->getCurrentLocale();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getCurrentLanguage(): string
    {
        return $this->getFactory()
            ->createLanguageReader()
            ->getLanguageByLocaleCode(
                $this->getCurrentLocale(),
            );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getLocales(): array
    {
        return Store::getInstance()->getLocales();
    }
}
