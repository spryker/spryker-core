<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;
use Symfony\Component\Finder\SplFileInfo;

class LocatorFacade extends AbstractDependencyFinder
{

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

        if (preg_match_all('/->(.*?)\(\)->facade\(\)/', $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $toBundle = $match[1];

                if (preg_match('/->/', $toBundle)) {
                    $foundParts = explode('->', $toBundle);
                    $toBundle = array_pop($foundParts);
                }

                $toBundle = ucfirst($toBundle);
                $this->addDependency($fileInfo, $toBundle, [DependencyTree::META_FOREIGN_LAYER => self::LAYER_BUSINESS]);
            }
        }
    }

}
