<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale;

use LogicException;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class LocaleConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @throw \LogicException
     *
     * @return string
     */
    public function getLocaleFile(): string
    {
        $realpath = realpath(__DIR__ . DIRECTORY_SEPARATOR . 'Business/Internal/Install/locales.txt');

        if ($realpath === false) {
            throw new LogicException('File not found: ' . $realpath);
        }

        return $realpath;
    }

    /**
     * Specification:
     *  - Returns list of available locales for backoffice UI.
     *
     * @api
     *
     * @return array<int, string>
     */
    public function getBackofficeUILocales(): array
    {
        return [
            'en_US',
            'de_DE',
        ];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getConsoleDefaultLocale(): string
    {
        return 'en_US';
    }
}
