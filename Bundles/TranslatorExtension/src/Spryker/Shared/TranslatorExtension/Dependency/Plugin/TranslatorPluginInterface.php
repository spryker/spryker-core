<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\TranslatorExtension\Dependency\Plugin;

use Symfony\Component\Translation\TranslatorInterface as LegacyTranslatorInterface;

/**
 * @deprecated Use {@link \Spryker\Shared\TranslatorExtension\Dependency\Plugin\Translator\TranslatorPluginInterface} instead.
 */
interface TranslatorPluginInterface extends LegacyTranslatorInterface
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
    public function trans($id, array $parameters = [], ?string $domain = null, ?string $locale = null): string;

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
    public function transChoice($id, $number, array $parameters = [], ?string $domain = null, ?string $locale = null): string;

    /**
     * Specification:
     * - Sets the current locale to Translator.
     *
     * @api
     *
     * @param string $locale
     *
     * @return void
     */
    public function setLocale($locale): void;

    /**
     * Specification:
     * - Returns the current locale of Translator.
     *
     * @api
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
     * @throws \Symfony\Component\Translation\Exception\InvalidArgumentException
     *
     * @return bool
     */
    public function has(string $keyName, string $locale): bool;
}
