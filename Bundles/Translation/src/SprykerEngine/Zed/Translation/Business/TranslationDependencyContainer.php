<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Translation\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\TranslationBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerEngine\Zed\Translation\TranslationConfig;

/**
 * @method TranslationBusiness getFactory()
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
        $translationFileFinder = $this->getFactory()->createTranslationFileFinder(
            $this->getConfig()->getPathPatterns()
        );

        $translationFileLoaderFactory = $this->getFactory()->createTranslationFileLoaderFactory();

        $translator = $this->getFactory()->createTranslator(
            $locale,
            $translationFileFinder,
            $translationFileLoaderFactory
        );

        return $translator;
    }

}
