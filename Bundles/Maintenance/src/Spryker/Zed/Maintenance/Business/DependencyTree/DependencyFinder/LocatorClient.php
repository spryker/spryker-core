<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;
use Symfony\Component\Finder\SplFileInfo;

class LocatorClient extends AbstractDependencyFinder
{

    const NO_LAYER = 'Default';

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

        if (preg_match_all('/->(.*?)\(\)->client\(\)/', $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $toBundle = $match[1];

                if (preg_match('/->/', $toBundle)) {
                    $foundParts = explode('->', $toBundle);
                    $toBundle = array_pop($foundParts);
                }

                $toBundle = ucfirst($toBundle);
                $foreignClassName = $this->getClassName($toBundle);
                $dependencyInformation = [
                    DependencyTree::META_FOREIGN_LAYER => self::NO_LAYER,
                    DependencyTree::META_FOREIGN_CLASS_NAME => $foreignClassName
                ];
                $this->addDependency($fileInfo, $toBundle, $dependencyInformation);
            }
        }
    }

    /**
     * @param $bundle
     *
     * @return string
     */
    private function getClassName($bundle)
    {
        return sprintf('Spryker\\Zed\\%1$s\\Business\\%1$sFacade', $bundle);
    }

}
