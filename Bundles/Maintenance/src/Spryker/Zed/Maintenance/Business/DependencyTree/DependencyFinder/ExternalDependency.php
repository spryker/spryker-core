<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;
use Symfony\Component\Finder\SplFileInfo;

class ExternalDependency extends AbstractDependencyFinder
{

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @throws \Exception
     *
     * @return void
     */
    public function addDependencies(SplFileInfo $fileInfo)
    {
        $content = $fileInfo->getContents();

        if (preg_match_all('/use (.*?)\\\(.*)|new\s\\\(.*?)\(|\s\\\(.*?)::(.*)/', $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                if (strpos($match[0], 'Spryker') !== false || strpos($match[0], 'Generated') !== false || strpos($match[0], 'Orm') !== false || strpos($match[0], 'use \\') !== false) {
                    continue;
                }

                $className = str_replace(['use ', ';', '(', ')', 'new '], '', $match[0]);
                $className = ltrim($className);
                $className = ltrim($className, '\\');

                if (strpos($className, '::') !== false) {
                    list($className, $method) = explode('::', $className);
                }

                if (preg_match('/(.*)\s(as|AS|As)\s/', $className, $match)) {
                    $className = trim($match[1]);
                }

                if (preg_match('/(.*)->/', $className, $match)) {
                    $className = trim($match[1]);
                }

                if (strpos($className, '_') === false && strpos($className, '\\') === false) {
                    continue;
                }

                $dependencyInformation[DependencyTree::META_FOREIGN_LAYER] = 'external';
                $dependencyInformation[DependencyTree::META_FOREIGN_CLASS_NAME] = $className;
                $dependencyInformation[DependencyTree::META_FOREIGN_IS_EXTERNAL] = true;

                $this->addDependency($fileInfo, 'external', $dependencyInformation);
            }
        }
    }

}
