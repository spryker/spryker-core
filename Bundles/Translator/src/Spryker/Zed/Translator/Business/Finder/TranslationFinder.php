<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Business\Finder;

use Spryker\Zed\Translator\TranslatorConfig;

class TranslationFinder implements TranslationFinderInterface
{
    public const FILE_FORMAT = 'csv';
    protected const FILE_PATTERN = '[a-z][a-z]_[A-Z][A-Z]';

    /**
     * @var \Spryker\Zed\Translator\TranslatorConfig
     */
    protected $translatorConfig;

    /**
     * @param \Spryker\Zed\Translator\TranslatorConfig $translatorConfig
     */
    public function __construct(TranslatorConfig $translatorConfig)
    {
        $this->translatorConfig = $translatorConfig;
    }

    /**
     * @return array
     */
    public function getTranslationFiles(): array
    {
        $translationPaths = [];
        $pathPatterns = $this->translatorConfig->getTranslationFilePathPatterns();
        foreach ($pathPatterns as $pattern) {
            $pattern = $pattern . static::FILE_PATTERN . '.' . $this->getFileFormat();
            $filePaths = glob($pattern);
            if (!$filePaths) {
                continue;
            }
            $translationPaths = array_merge($translationPaths, $filePaths);
        }

        return $translationPaths;
    }

    /**
     * @return string
     */
    public function getFileFormat(): string
    {
        return static::FILE_FORMAT;
    }
}
