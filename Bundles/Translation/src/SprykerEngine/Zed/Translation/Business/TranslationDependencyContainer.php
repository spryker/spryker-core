<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Translation\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\TranslationBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerEngine\Zed\Translation\TranslationConfig;

/**
 * @method TranslationConfig getConfig()
 */
class TranslationDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @param string $locale
     *
     * @return TranslatorInterface
     */
    public function createTranslator($locale)
    {
        $translationFileFinder = new TranslationFileFinder(
            $this->getConfig()->getPathPatterns()
        );

        $translationFileLoaderFactory = new TranslationFileLoaderFactory();

        $translator = new Translator(
            $locale,
            $translationFileFinder,
            $translationFileLoaderFactory
        );

        return $translator;
    }

}
