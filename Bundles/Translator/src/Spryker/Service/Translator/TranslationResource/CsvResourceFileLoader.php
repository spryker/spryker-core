<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Translator\TranslationResource;

use Spryker\Service\Translator\TranslationFinder\TranslationFileFinderInterface;
use Spryker\Service\Translator\TranslationLoader\TranslationLoaderInterface;
use Spryker\Service\Translator\TranslatorConfig;

class CsvResourceFileLoader implements TranslationResourceFileLoaderInterface
{
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
     * @param \Spryker\Service\Translator\TranslationLoader\TranslationLoaderInterface $translationLoader
     * @param \Spryker\Service\Translator\TranslationFinder\TranslationFileFinderInterface $translationFileFinder
     * @param \Spryker\Service\Translator\TranslatorConfig $translatorConfig
     */
    public function __construct(
        TranslationLoaderInterface $translationLoader,
        TranslationFileFinderInterface $translationFileFinder,
        TranslatorConfig $translatorConfig
    ) {
        $this->translationLoader = $translationLoader;
        $this->translationFileFinder = $translationFileFinder;
        $this->translatorConfig = $translatorConfig;
    }

    /**
     * @return string|null
     */
    public function getDomain(): ?string
    {
        return null;
    }

    /**
     * @param string $filename
     *
     * @return string|null
     */
    public function findLocaleFromFilename(string $filename): ?string
    {
        $pathInfo = pathinfo($filename);

        return $pathInfo['filename'];
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
        return $this->translationFileFinder->findFilesByGlobPatterns($this->translatorConfig->getTranslationFilePathPatterns());
    }
}
