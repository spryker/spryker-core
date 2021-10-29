<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Locale;

interface LocaleClientInterface
{
    /**
     * Specification:
     * - Returns current locale name.
     *
     * @api
     *
     * @return string
     */
    public function getCurrentLocale();

    /**
     * Specification:
     * - Returns current language based on current locale.
     *
     * @api
     *
     * @return string
     */
    public function getCurrentLanguage(): string;

    /**
     * Specification:
     * - Returns a list of locale codes for current store.
     *
     * @api
     *
     * @return array<string>
     */
    public function getLocales(): array;
}
