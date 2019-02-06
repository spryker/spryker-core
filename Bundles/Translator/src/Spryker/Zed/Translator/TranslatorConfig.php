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
     * @return string[]
     */
    public function getZedTranslationFilePathPatterns(): array
    {
        return array_merge($this->getCoreZedTranslationFilePathPatterns(), $this->getProjectZedTranslationFilePathPatterns());
    }

    /**
     * @return string[]
     */
    public function getCoreZedTranslationFilePathPatterns(): array
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
    public function getProjectZedTranslationFilePathPatterns(): array
    {
        return $this->get(TranslatorConstants::TRANSLATION_ZED_FILE_PATH_PATTERNS, []);
    }

    /**
     * @param string $localeCode
     *
     * @return string[]
     */
    public function getZedFallbackLocales(string $localeCode): array
    {
        $fallbackLocales = $this->get(TranslatorConstants::TRANSLATION_ZED_FALLBACK_LOCALES, []);

        return $fallbackLocales[$localeCode] ?? [];
    }

    /**
     * @return string
     */
    public function getZedTranslatorCacheDirectory(): string
    {
        return $this->get(TranslatorConstants::TRANSLATION_ZED_CACHE_DIRECTORY);
    }

    /**
     * @return string
     */
    public function getZedCsvFileDelimiter(): string
    {
        return static::ZED_CSV_FILE_DELIMITER;
    }
}
