<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\TranslationResource;

use Spryker\Zed\Translator\Business\TranslationFinder\TranslationFileFinderInterface;
use Spryker\Zed\Translator\Business\TranslationLoader\TranslationLoaderInterface;
use Spryker\Zed\Translator\TranslatorConfig;

class CsvResourceFileLoader implements TranslationResourceFileLoaderInterface
{
    /**
     * @var \Spryker\Zed\Translator\Business\TranslationLoader\TranslationLoaderInterface
     */
    protected $translationLoader;

    /**
     * @var \Spryker\Zed\Translator\Business\TranslationFinder\TranslationFileFinderInterface
     */
    protected $translationFileFinder;

    /**
     * @var \Spryker\Zed\Translator\TranslatorConfig
     */
    protected $translatorConfig;

    /**
     * @param \Spryker\Zed\Translator\Business\TranslationLoader\TranslationLoaderInterface $translationLoader
     * @param \Spryker\Zed\Translator\Business\TranslationFinder\TranslationFileFinderInterface $translationFileFinder
     * @param \Spryker\Zed\Translator\TranslatorConfig $translatorConfig
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
     * @return \Spryker\Zed\Translator\Business\TranslationLoader\TranslationLoaderInterface
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
