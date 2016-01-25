<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;
use Symfony\Component\Finder\SplFileInfo;

class UseStatement extends AbstractDependencyFinder
{

    const NO_LAYER = 'Default';
    const BUNDLE = 'bundle';

    /**
     * @param SplFileInfo $fileInfo
     *
     * @throws \Exception
     *
     * @return void
     */
    public function addDependencies(SplFileInfo $fileInfo)
    {
        $content = $fileInfo->getContents();

        if (!preg_match_all('/use Spryker\\\(?<application>.*?)\\\(?<bundle>.*?)\\\(?<layerOrFileName>.*?);/', $content, $matches, PREG_SET_ORDER)) {
            return;
        }
        foreach ($matches as $match) {
            $className = str_replace(['use ', ';'], '', $match[0]);
            $toBundle = $match[self::BUNDLE];
            $layer = $this->getLayerFromUseStatement($match);
            $dependencyInformation[DependencyTree::META_FOREIGN_LAYER] = $layer;
            $dependencyInformation[DependencyTree::META_FOREIGN_CLASS_NAME] = $className;

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
            if (in_array($layer, [self::LAYER_BUSINESS, self::LAYER_COMMUNICATION, self::LAYER_PERSISTENCE])) {
                return $layer;
            }
        }

        return self::NO_LAYER;
    }

}
