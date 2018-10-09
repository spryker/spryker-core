<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\Translator;

use Spryker\Zed\Translator\Business\Finder\TranslationFinderInterface;
use Symfony\Component\Translation\Translator as SymfonyTranslator;

class Translator extends SymfonyTranslator implements TranslatorInterface
{
    /**
     * @var \Spryker\Zed\Translator\Business\Finder\TranslationFinderInterface
     */
    protected $translationFinder;

    /**
     * @var bool
     */
    protected $extractedFlag = false;

    /**
     * @param string $locale
     *
     * @return void
     */
    protected function initializeCatalogue($locale): void
    {
        $this->extractResources();

        parent::initializeCatalogue($locale);
    }

    /**
     * @param \Spryker\Zed\Translator\Business\Finder\TranslationFinderInterface $translationFinder
     *
     * @return void
     */
    public function setLazyLoadResources(TranslationFinderInterface $translationFinder): void
    {
        $this->translationFinder = $translationFinder;
    }

    /**
     * @return void
     */
    public function extractResources(): void
    {
        if ($this->translationFinder !== null && $this->extractedFlag === false) {
            $files = $this->translationFinder->getTranslationFiles();
            foreach ($files as $file) {
                $locale = basename($file, '.csv');
                $this->addResource($this->translationFinder->getFileFormat(), $file, $locale);
            }
            $this->extractedFlag = true;
        }
    }

    /**
     * @param array $locales
     *
     * @return void
     */
    public function generateCache(array $locales): void
    {
        foreach ($locales as $locale) {
            $this->loadCatalogue($locale);
        }
    }
}
