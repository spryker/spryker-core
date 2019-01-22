<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Translator;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Service\Translator\Translator\TranslatorInterface;

interface TranslatorServiceInterface
{
    /**
     * Specification:
     *  - Returns Translator object.
     *
     * @api
     *
     * @return \Spryker\Service\Translator\Translator\TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface;

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
     *  - Finds a key in the dictionary.
     *
     * @api
     *
     * @param string $keyName
     *
     * @return bool
     */
    public function hasTranslation($keyName): bool;

    /**
     * Specification:
     *  - Finds a translation for the specified key for the particular locale.
     *
     * @api
     *
     * @param string $keyName
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return string
     */
    public function translate($keyName, array $data = [], ?LocaleTransfer $localeTransfer = null): string;
}
