<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle;

use Symfony\Component\Finder\Glob;
use Symfony\Component\Finder\SplFileInfo;

class NamespaceExtractor implements NamespaceExtractorInterface
{
    /**
     * @param \Symfony\Component\Finder\SplFileInfo $directory
     * @param string $baseDirectoryGlobPattern
     *
     * @return string
     */
    public function fromDirectory(SplFileInfo $directory, $baseDirectoryGlobPattern)
    {
        $basePathRegularExpression = $this->getRegularExpressionForGlobPattern($baseDirectoryGlobPattern);
        $baseNamespace = preg_replace($basePathRegularExpression, '', $directory->getPath());
        $baseNamespace = str_replace(DIRECTORY_SEPARATOR, '\\', $baseNamespace);
        $namespace = trim($baseNamespace, '\\') . '\\' . $directory->getBasename();

        return $namespace;
    }

    /**
     * @param string $globPattern
     *
     * @return string
     */
    protected function getRegularExpressionForGlobPattern($globPattern)
    {
        $regularExpression = Glob::toRegex($globPattern);
        $regularExpression = substr_replace($regularExpression, '#', -2);

        return $regularExpression;
    }
}
