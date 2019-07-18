<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyFinder;

use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;
use Symfony\Component\Finder\SplFileInfo;

class UseStatement extends AbstractDependencyFinder
{
    public const LAYER_DEFAULT = 'Default';
    public const BUNDLE = 'bundle';

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return void
     */
    public function addDependencies(SplFileInfo $fileInfo)
    {
        $content = $fileInfo->getContents();

        if (preg_match_all('/use (Spryker|SprykerSdk|SprykerShop|SprykerEco|Orm)\\\(?<application>.*?)\\\(?<bundle>.*?)\\\(?<layerOrFileName>.*?);/', $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $className = str_replace(['use ', ';'], '', $match[0]);
                $toBundle = $match[static::BUNDLE];
                $layer = $this->getLayerFromUseStatement($match);
                $dependencyInformation[DependencyTree::META_FOREIGN_LAYER] = $layer;
                $dependencyInformation[DependencyTree::META_FOREIGN_CLASS_NAME] = $className;

                $this->addDependency($fileInfo, $toBundle, $dependencyInformation);
            }
        }

        if (preg_match('/use Spryker\\\Shared\\\Config/', $content)) {
            $toBundle = 'Config';
            $dependencyInformation[DependencyTree::META_FOREIGN_LAYER] = '';
            $dependencyInformation[DependencyTree::META_FOREIGN_CLASS_NAME] = 'Spryker\\Shared\\Config';

            $this->addDependency($fileInfo, $toBundle, $dependencyInformation);
        }
    }

    /**
     * @param string $match
     *
     * @return string
     */
    protected function getLayerFromUseStatement($match)
    {
        $relativeClassName = $match[3];
        if (preg_match('/\\\/', $relativeClassName)) {
            $classNameParts = explode('\\', $relativeClassName);
            $layer = array_shift($classNameParts);
            if (in_array($layer, [static::LAYER_BUSINESS, static::LAYER_COMMUNICATION, static::LAYER_PERSISTENCE])) {
                return $layer;
            }
        }

        return static::LAYER_DEFAULT;
    }
}
