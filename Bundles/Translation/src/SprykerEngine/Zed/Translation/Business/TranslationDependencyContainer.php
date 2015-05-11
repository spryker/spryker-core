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

        $translationFileFinder = $this->getFactory()->createTranslationFileFinder();

        foreach ($translationFileFinder->getTranslationFilePaths() as $path) {
            $pathParts = explode(DIRECTORY_SEPARATOR, $path);

            list($locale, $format) = explode('.', array_pop($pathParts));

            if (!$translator->hasLoader($format)) {
                $translator->addLoader(
                    $format,
                    $this->getConfig()->getLoader($format)
                );
            }

            $translator->addResource($format, $path, $locale);
        }

        return $translator;
    }
}