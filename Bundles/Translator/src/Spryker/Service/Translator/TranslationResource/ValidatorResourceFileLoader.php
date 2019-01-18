<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Translator\TranslationResource;

use Spryker\Service\Translator\TranslationFinder\TranslationFileFinderInterface;
use Spryker\Service\Translator\TranslationLoader\TranslationLoaderInterface;
use Spryker\Service\Translator\TranslatorConfig;

class ValidatorResourceFileLoader implements TranslationResourceFileLoaderInterface
{
    protected const TRANSLATION_DOMAIN = 'validators';

    /**
     * @var \Spryker\Service\Translator\TranslationLoader\TranslationLoaderInterface
     */
    protected $translationLoader;

    /**
     * @var \Spryker\Service\Translator\TranslationFinder\TranslationFileFinderInterface
     */
    protected $translationFileFinder;

    /**
     * @var \Spryker\Service\Translator\TranslatorConfig
     */
    protected $translatorConfig;

    /**
     * @var array
     */
    protected $locales;

    /**
     * @param \Spryker\Service\Translator\TranslationLoader\TranslationLoaderInterface $translationLoader
     * @param \Spryker\Service\Translator\TranslationFinder\TranslationFileFinderInterface $translationFileFinder
     * @param \Spryker\Service\Translator\TranslatorConfig $translatorConfig
     * @param array $locales
     */
    public function __construct(
        TranslationLoaderInterface $translationLoader,
        TranslationFileFinderInterface $translationFileFinder,
        TranslatorConfig $translatorConfig,
        array $locales
    ) {
        $this->translationLoader = $translationLoader;
        $this->translationFileFinder = $translationFileFinder;
        $this->translatorConfig = $translatorConfig;
        $this->locales = $locales;
    }

    /**
     * @return string|null
     */
    public function getDomain(): ?string
    {
        return static::TRANSLATION_DOMAIN;
    }

    /**
     * @param string $filename
     *
     * @return string|null
     */
    public function getLocaleFromFilename(string $filename): ?string
    {
        $pathInfo = pathinfo($filename);
        $filenameParts = explode('.', $pathInfo['filename']);

        return $this->locales[$filenameParts[1]] ?? null;
    }

    /**
     * @return \Spryker\Service\Translator\TranslationLoader\TranslationLoaderInterface
     */
    public function getLoader(): TranslationLoaderInterface
    {
        return $this->translationLoader;
    }

    /**
     * @return string[]
     */
    public function getFilePaths(): array
    {
        return $this->translationFileFinder->findFilesByGlobPatterns($this->translatorConfig->getValidatorTranslationFilePatterns());
    }
}
