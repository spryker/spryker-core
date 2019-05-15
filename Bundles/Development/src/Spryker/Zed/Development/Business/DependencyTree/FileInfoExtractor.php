<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree;

use Exception;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\Word\CamelCaseToDash;

class FileInfoExtractor
{
    public const LAYER = 'Default';

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return string
     */
    public function getApplicationNameFromFileInfo(SplFileInfo $fileInfo)
    {
        $classNameParts = $this->getClassNameParts($fileInfo);

        return $classNameParts[1];
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return string
     */
    public function getBundleNameFromFileInfo(SplFileInfo $fileInfo)
    {
        $classNameParts = $this->getClassNameParts($fileInfo);

        return $classNameParts[2];
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return string
     */
    public function getLayerNameFromFileInfo(SplFileInfo $fileInfo)
    {
        $classNameParts = $this->getClassNameParts($fileInfo);

        if (!isset($classNameParts[3])) {
            return 'tests';
        }
        $layer = $classNameParts[3];
        if (in_array($layer, ['Business', 'Communication', 'Persistence'])) {
            return $layer;
        }

        return static::LAYER;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return string
     */
    public function getClassNameFromFile(SplFileInfo $fileInfo)
    {
        return substr(implode('\\', $this->getClassNameParts($fileInfo)), 0, -4);
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return string
     */
    public function getOrganizationFromFile(SplFileInfo $fileInfo)
    {
        $classNameParts = $this->getClassNameParts($fileInfo);
        $filter = new CamelCaseToDash();

        return strtolower($filter->filter($classNameParts[0]));
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @throws \Exception
     *
     * @return array
     */
    private function getClassNameParts(SplFileInfo $fileInfo)
    {
        $pathParts = explode(DIRECTORY_SEPARATOR, $fileInfo->getPathname());
        $sourceDirectoryPosition = array_search('src', $pathParts);
        if ($sourceDirectoryPosition) {
            return array_slice($pathParts, $sourceDirectoryPosition + 1);
        }

        $testsDirectoryPosition = array_search('tests', $pathParts);
        if ($testsDirectoryPosition) {
            if (array_search('_support', $pathParts)) {
                return ['Spryker', 'tests', $pathParts[$testsDirectoryPosition - 1], '_support'];
            }

            return array_slice($pathParts, $testsDirectoryPosition + 2);
        }

        throw new Exception(sprintf('Could not extract class name parts from file "%s".', $fileInfo->getPathname()));
    }
}
