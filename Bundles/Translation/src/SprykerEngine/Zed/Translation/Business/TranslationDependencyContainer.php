<?php

namespace SprykerEngine\Zed\Translation\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Translation\Business\TranslatorInterface;
use SprykerEngine\Zed\Translation\Business\Model\TranslationFileInterface;

/**
 * @method Factory getFactory()
 */
class TranslationDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @param string $locale
     *
     * @return TranslatorInterface
     */
    public function getTranslator($locale)
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