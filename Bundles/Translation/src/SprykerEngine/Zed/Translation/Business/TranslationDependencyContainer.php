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
        $translator = $this->getFactory()->createTranslator($locale);

        foreach ($this->getConfig()->getLoaders() as $format => $loader) {
            $translator->addLoader($format, $loader);
        }

        $translationFileFinder = $this->getFactory()->createTranslationFileFinder(
            $this
        );

        foreach ($translationFileFinder->getTranslationFiles() as $file) {
            $translator->addResource(
                $file->getFormat(),
                $file->getPath(),
                $file->getLocale()
            );
        }

        return $translator;
    }

    /**
     * @param string $path
     *
     * @return TranslationFileInterface
     */
    public function getTranslationFile($path)
    {
        $translationFile = $this->getFactory()->createModelTranslationFile();

        $translationFile->setPath($path);

        $pathParts = explode(DIRECTORY_SEPARATOR, $path);

        list($locale, $type) = explode('.', array_pop($pathParts));

        $translationFile
            ->setLocale($locale)
            ->setFormat($type);

        return $translationFile;
    }
}