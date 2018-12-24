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
    protected const DELIMITER = ',';
    protected const DEFAULT_FALLBACK_LOCALES = ['en_US'];

    /**
     * @return array
     */
    public function getTranslationFilePathPatterns(): array
    {
        return array_merge($this->getCoreTranslationFilePathPatterns(), $this->getProjectTranslationFilePathPatterns());
    }

    /**
     * @param string $country
     *
     * @return string
     */
    public function getValidatorsTranslationPath(string $country): string
    {
        return sprintf(
            APPLICATION_VENDOR_DIR . '/symfony/validator/Resources/translations/validators.%s.xlf',
            $country
        );
    }

    /**
     * @return array
     */
    public function getCoreTranslationFilePathPatterns(): array
    {
        return [
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/*/Zed/*/Translation/',
            APPLICATION_VENDOR_DIR . '/spryker/*/src/*/Zed/*/Translation/',
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/data/translation/Zed/',
            APPLICATION_VENDOR_DIR . '/spryker/*/data/translation/Zed/',
        ];
    }

    /**
     * @return array
     */
    public function getProjectTranslationFilePathPatterns(): array
    {
        return [
            APPLICATION_ROOT_DIR . '/data/import/translation/Zed/*/',
            APPLICATION_ROOT_DIR . '/data/translation/Zed/*/',
        ];
    }

    /**
     * @param string $localeCode
     *
     * @return array
     */
    public function getFallbackLocales(string $localeCode): array
    {
        $fallbackLocales = $this->get(TranslatorConstants::FALLBACK_LOCALES, []);

        return $fallbackLocales[$localeCode] ?? $this->getDefaultFallbackLocales();
    }

    /**
     * @return array
     */
    public function getDefaultFallbackLocales(): array
    {
        return static::DEFAULT_FALLBACK_LOCALES;
    }

    /**
     * @return string
     */
    public function getCacheDir(): string
    {
        return APPLICATION_ROOT_DIR . '/data/translations/Zed/';
    }

    /**
     * @return string
     */
    public function getDelimiter(): string
    {
        return static::DELIMITER;
    }
}
