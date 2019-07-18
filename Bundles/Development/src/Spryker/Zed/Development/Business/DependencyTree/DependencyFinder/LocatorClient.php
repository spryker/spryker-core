<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyFinder;

use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;
use Symfony\Component\Finder\SplFileInfo;

class LocatorClient extends AbstractDependencyFinder
{
    public const NO_LAYER = 'Default';
    public const BUNDLE = 'bundle';

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return void
     */
    public function addDependencies(SplFileInfo $fileInfo)
    {
        $content = $fileInfo->getContents();

        if (!preg_match_all('/->(?<bundle>.*?)\(\)->client\(\)/', $content, $matches, PREG_SET_ORDER)) {
            return;
        }

        foreach ($matches as $match) {
            $toBundle = $match[static::BUNDLE];

            if (preg_match('/->/', $toBundle)) {
                $foundParts = explode('->', $toBundle);
                $toBundle = array_pop($foundParts);
            }

            $toBundle = ucfirst($toBundle);
            $foreignClassName = $this->getClassName($toBundle);
            $dependencyInformation = [
                DependencyTree::META_FOREIGN_LAYER => static::NO_LAYER,
                DependencyTree::META_FOREIGN_CLASS_NAME => $foreignClassName,
            ];
            $this->addDependency($fileInfo, $toBundle, $dependencyInformation);
        }
    }

    /**
     * @param string $bundle
     *
     * @return string
     */
    private function getClassName($bundle)
    {
        return sprintf('Spryker\\Client\\%1$s\\\\%1$sClient', $bundle);
    }
}
