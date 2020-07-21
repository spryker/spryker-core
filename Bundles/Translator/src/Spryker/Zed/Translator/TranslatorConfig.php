<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator;

use Spryker\Shared\Translator\TranslatorConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class TranslatorConfig extends AbstractBundleConfig
{
    public const ZED_CSV_FILE_DELIMITER = ',';

    /**
     * @api
     *
     * @return string[]
     */
    public function getTranslationFilePathPatterns(): array
    {
        return array_merge($this->getCoreTranslationFilePathPatterns(), $this->getProjectTranslationFilePathPatterns());
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getCoreTranslationFilePathPatterns(): array
    {
        return [
            APPLICATION_VENDOR_DIR . '/spryker/*/data/translation/Zed/[a-z][a-z]_[A-Z][A-Z].csv',
        ];
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getValidatorTranslationFilePatterns(): array
    {
        return [
            APPLICATION_VENDOR_DIR . '/symfony/validator/Resources/translations/validators.[a-z][a-z].xlf',
        ];
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getProjectTranslationFilePathPatterns(): array
    {
        if ($this->getConfig()->hasKey(TranslatorConstants::TRANSLATION_ZED_FILE_PATH_PATTERNS)) {
            return $this->get(TranslatorConstants::TRANSLATION_ZED_FILE_PATH_PATTERNS);
        }

        return [
            sprintf('%s/src/Pyz/Zed/Translator/data/*/[a-z][a-z]_[A-Z][A-Z].csv', APPLICATION_ROOT_DIR),
        ];
    }

    /**
     * @api
     *
     * @param string $localeCode
     *
     * @return string[]
     */
    public function getFallbackLocales(string $localeCode): array
    {
        $fallbackLocales = $this->get(TranslatorConstants::TRANSLATION_ZED_FALLBACK_LOCALES, []);

        return $fallbackLocales[$localeCode] ?? [];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTranslatorCacheDirectory(): string
    {
        if ($this->getConfig()->hasKey(TranslatorConstants::TRANSLATION_ZED_CACHE_DIRECTORY)) {
            return $this->get(TranslatorConstants::TRANSLATION_ZED_CACHE_DIRECTORY);
        }

        return sprintf('%s/src/Generated/Zed/Translator/codeBucket', APPLICATION_ROOT_DIR);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCsvFileDelimiter(): string
    {
        return static::ZED_CSV_FILE_DELIMITER;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isZedTranslatorDebugEnabled(): bool
    {
        return $this->get(TranslatorConstants::TRANSLATION_ZED_DEBUG_ENABLED, false);
    }
}
