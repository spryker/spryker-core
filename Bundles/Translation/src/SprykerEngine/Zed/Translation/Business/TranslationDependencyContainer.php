<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Translation\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerEngine\Zed\Kernel\Business\Factory;

/**
 * @method Factory getFactory()
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
            null,
            $translationFileFinder,
            $translationFileLoaderFactory
        );

        return $translator;
    }

}
