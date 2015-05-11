<?php

namespace SprykerEngine\Zed\Translation\Business;

use SprykerEngine\Zed\Translation\Business\Model\TranslationFileInterface;
use SprykerEngine\Zed\Translation\TranslationConfig;

class TranslationFileFinder
{
    /**
     * @var TranslationDependencyContainer
     */
    protected $dependencyContainer;

    public function __construct(TranslationDependencyContainer $dependencyContainer)
    {
        $this->dependencyContainer = $dependencyContainer;
    }

    /**
     * @return TranslationFileInterface[]
     */
    public function getTranslationFiles()
    {
        $translationFilePaths = [];
        $translationFiles     = [];

        foreach ($this->getPathPatterns() as $pathPattern) {
            $paths = glob($pathPattern);

            $translationFilePaths = array_merge($translationFilePaths, $paths);
        }

        foreach ($translationFilePaths as $path) {
            $translationFiles[] = $this->getTranslationFile($path);
        }

        return $translationFiles;
    }

    /**
     * @param string $path
     *
     * @return TranslationFileInterface
     */
    protected function getTranslationFile($path)
    {
        return $this->dependencyContainer->getTranslationFile($path);
    }

    /**
     * @return string[]
     */
    protected function getPathPatterns()
    {
        $formats = TranslationConfig::getFormats();

        $pathPatterns = [];

        foreach ($formats as $format) {
            $pathPatterns[] = APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/*/Zed/*/Translation/*.' . $format;
            $pathPatterns[] = APPLICATION_SOURCE_DIR . '/*/Zed/*/Translation/*.' . $format;
        }

        return $pathPatterns;
    }
}