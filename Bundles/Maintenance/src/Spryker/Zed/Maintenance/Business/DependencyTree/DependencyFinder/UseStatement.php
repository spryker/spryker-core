<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;
use Symfony\Component\Finder\SplFileInfo;

class UseStatement extends AbstractDependencyFinder
{

    const NO_LAYER = 'noLayer';

    /**
     * @param SplFileInfo $fileInfo
     *
     * @throws \Exception
     *
     * @return void
     */
    public function findDependencies(SplFileInfo $fileInfo)
    {
        $content = $fileInfo->getContents();

        if (preg_match_all('/use Spryker\\\(.*?)\\\(.*?)\\\(.*?);/', $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $toBundle = $match[2];
                $layer = $this->getLayerFromUseStatement($match);
                $meta = [
                    DependencyTree::META_FOREIGN_LAYER => self::NO_LAYER
                ];
                if ($layer) {
                    $meta[DependencyTree::META_FOREIGN_LAYER] = $layer;
                }

                $this->addDependency($fileInfo, $toBundle, $meta);
            }
        }
    }

    /**
     * @param string $match
     *
     * @return bool|string
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

        return false;
    }

}
