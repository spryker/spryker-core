<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Translation\Business;

class TranslationFileFinder
{

    /**
     * @var
     */
    protected $pathPatterns;

    /**
     * @param array $pathPatterns
     */
    public function __construct(array $pathPatterns)
    {
        $this->pathPatterns = $pathPatterns;
    }

    /**
     * @return string[]
     */
    public function getTranslationFilePaths()
    {
        $translationFilePaths = [];

        foreach ($this->pathPatterns as $pathPattern) {
            $paths = glob($pathPattern);

            $translationFilePaths = array_merge($translationFilePaths, $paths);
        }

        return $translationFilePaths;
    }

}
