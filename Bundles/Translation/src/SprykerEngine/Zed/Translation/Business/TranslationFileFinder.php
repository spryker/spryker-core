<?php

namespace SprykerEngine\Zed\Translation\Business;

use SprykerEngine\Zed\Translation\Business\Model\TranslationFile;
use SprykerEngine\Zed\Translation\Business\Model\TranslationFileInterface;
use SprykerEngine\Zed\Translation\TranslationConfig;

class TranslationFileFinder
{
    /**
     * @return string[]
     */
    public function getTranslationFilePaths()
    {
        $translationFilePaths = [];

        $pathPatterns = TranslationConfig::getPathPatterns();

        foreach ($pathPatterns as $pathPattern) {
            $paths = glob($pathPattern);

            $translationFilePaths = array_merge($translationFilePaths, $paths);
        }

        return $translationFilePaths;
    }
}