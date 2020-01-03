<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business;

use Spryker\Shared\Translator\TranslatorInterface;

interface TranslatorFacadeInterface extends TranslatorInterface
{
    /**
     * Specification:
     *  - Extends Application with Translator instance.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return void
     */
    public function prepareTranslatorService(): void;

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
    public function trans($id, array $parameters = [], $domain = null, $locale = null): string;

    /**
     * Specification:
     * - Translates the given choice message by choosing a translation according to a number.
     *
     * @api
     *
     * @param string $id
     * @param int $number
     * @param array $parameters
     * @param string|null $domain
     * @param string|null $locale
     *
     * @return string
     */
    public function transChoice($id, $number, array $parameters = [], $domain = null, $locale = null): string;

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @see \Symfony\Contracts\Translation\TranslatorInterface
     *
     * Specification:
     * - Sets the current locale to Translator.
     *
     * @param string $locale
     *
     * @return void
     */
    public function setLocale($locale): void;

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @see \Symfony\Contracts\Translation\TranslatorInterface
     *
     * Specification:
     * - Returns the current locale of Translator.
     *
     * @return string The locale
     */
    public function getLocale(): string;

    /**
     * Specification:
     * - Check if we have key in the catalogue by locale and key.
     *
     * @api
     *
     * @param string $keyName
     * @param string $locale
     *
     * @return bool
     */
    public function has(string $keyName, string $locale): bool;
}
