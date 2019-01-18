<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Translator\TranslationFinder;

class TranslationFileFinder implements TranslationFileFinderInterface
{
    /**
     * @param string[] $translationFilePathPatterns
     *
     * @return string[]
     */
    public function findFilesByGlobPatterns(array $translationFilePathPatterns): array
    {
        $translationFilePaths = [];
        foreach ($translationFilePathPatterns as $translationFilePathPattern) {
            $translationFilePaths = array_merge($translationFilePaths, glob($translationFilePathPattern, GLOB_NOSORT));
        }

        return array_unique(array_filter($translationFilePaths));
    }
}
