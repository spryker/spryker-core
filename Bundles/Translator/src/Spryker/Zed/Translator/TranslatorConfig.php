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
    protected const ZED_TRANSLATOR_DEBUG_ENABLED = false;

    /**
     * @return string[]
     */
    public function getTranslationFilePathPatterns(): array
    {
        return array_merge($this->getCoreTranslationFilePathPatterns(), $this->getProjectTranslationFilePathPatterns());
    }

    /**
     * @return string[]
     */
    public function getCoreTranslationFilePathPatterns(): array
    {
        return [
            APPLICATION_VENDOR_DIR . '/spryker/*/data/translation/Zed/[a-z][a-z]_[A-Z][A-Z].csv',
        ];
    }

    /**
     * @return string[]
     */
    public function getValidatorTranslationFilePatterns(): array
    {
        return [
            APPLICATION_VENDOR_DIR . '/symfony/validator/Resources/translations/validators.[a-z][a-z].xlf',
        ];
    }

    /**
     * @return string[]
     */
    public function getProjectTranslationFilePathPatterns(): array
    {
        return $this->get(TranslatorConstants::TRANSLATION_ZED_FILE_PATH_PATTERNS, []);
    }

    /**
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
     * @return string
     */
    public function getTranslatorCacheDirectory(): string
    {
        return $this->get(TranslatorConstants::TRANSLATION_ZED_CACHE_DIRECTORY);
    }

    /**
     * @return string
     */
    public function getCsvFileDelimiter(): string
    {
        return static::ZED_CSV_FILE_DELIMITER;
    }

    /**
     * @return bool
     */
    public function isZedTranslatorDebugEnabled(): bool
    {
        return static::ZED_TRANSLATOR_DEBUG_ENABLED;
    }
}
