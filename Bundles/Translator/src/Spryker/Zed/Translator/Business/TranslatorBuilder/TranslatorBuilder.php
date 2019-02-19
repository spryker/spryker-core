<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\TranslatorBuilder;

use Spryker\Zed\Translator\Business\TranslationResource\TranslationResourceFileLoaderInterface;
use Spryker\Zed\Translator\Business\Translator\TranslatorInterface;

class TranslatorBuilder implements TranslatorBuilderInterface
{
    /**
     * @var array|\Spryker\Zed\Translator\Business\TranslationResource\TranslationResourceFileLoaderInterface[]
     */
    protected $translationResourceFileLoaders;

    /**
     * @param \Spryker\Zed\Translator\Business\TranslationResource\TranslationResourceFileLoaderInterface[] $translationResourceFileLoaders
     */
    public function __construct(array $translationResourceFileLoaders = [])
    {
        $this->translationResourceFileLoaders = $translationResourceFileLoaders;
    }

    /**
     * @param \Spryker\Zed\Translator\Business\Translator\TranslatorInterface $translator
     *
     * @return \Spryker\Zed\Translator\Business\Translator\TranslatorInterface
     */
    public function buildTranslator(TranslatorInterface $translator): TranslatorInterface
    {
        $translator = $this->initializeResources($translator);

        return $translator;
    }

    /**
     * @param \Spryker\Zed\Translator\Business\Translator\TranslatorInterface $translator
     *
     * @return \Spryker\Zed\Translator\Business\Translator\TranslatorInterface
     */
    protected function initializeResources(TranslatorInterface $translator): TranslatorInterface
    {
        foreach ($this->translationResourceFileLoaders as $translationResourceFileLoader) {
            $loaderFormat = $translationResourceFileLoader->getLoader()->getFormat();
            $translator->addLoader($loaderFormat, $translationResourceFileLoader->getLoader());

            $this->addResources($translator, $translationResourceFileLoader, $loaderFormat);
        }

        return $translator;
    }

    /**
     * @param \Spryker\Zed\Translator\Business\Translator\TranslatorInterface $translator
     * @param \Spryker\Zed\Translator\Business\TranslationResource\TranslationResourceFileLoaderInterface $translationResourceFileLoader
     * @param string $loaderFormat
     *
     * @return \Spryker\Zed\Translator\Business\Translator\TranslatorInterface
     */
    protected function addResources(
        TranslatorInterface $translator,
        TranslationResourceFileLoaderInterface $translationResourceFileLoader,
        string $loaderFormat
    ): TranslatorInterface {
        foreach ($translationResourceFileLoader->getFilePaths() as $filePath) {
            $translationResourceLocale = $translationResourceFileLoader->findLocaleFromFilename($filePath);
            if (!$translationResourceLocale) {
                continue;
            }

            $translator->addResource($loaderFormat, $filePath, $translationResourceLocale, $translationResourceFileLoader->getDomain());
        }

        return $translator;
    }
}
