<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Translator;

interface TranslatorServiceInterface
{
    /**
     * Specification:
     * - Translates the given message.
     *
     * @api
     *
     * @param string $id
     * @param array $parameters
     * @param string|null $domain
     * @param string|null $locale
     *
     * @return string
     */
    public function translate(string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string;

    /**
     * Specification:
     * - Generates translation cache for all locales of the current store.
     *
     * @api
     *
     * @return void
     */
    public function generateTranslationCache(): void;

    /**
     * Specification:
     * - Clears Zed's translation cache.
     *
     * @api
     *
     * @return void
     */
    public function cleanTranslationCache(): void;

    /**
     * Specification:
     *  - Finds a key in the dictionary for given locale (it does not take into account the fallback mechanism).
     *
     * @api
     *
     * @param string $message
     * @param string $localeName
     *
     * @return bool
     */
    public function hasTranslation(string $message, string $localeName): bool;
}
