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
        $file = new \PHP_CodeSniffer_File($fileInfo->getPathname(), [], [], new \PHP_CodeSniffer());
        $file->start($content);
        $tokens = $file->getTokens();
        $pointer = 0;

        $classNames = [];
        while ($foundPosition = $file->findNext([T_NEW, T_USE, T_DOUBLE_COLON], $pointer)) {
            $pointer = $foundPosition + 1;
            $currentToken = $tokens[$foundPosition];

            if ($currentToken['type'] === 'T_NEW' || $currentToken['type'] === 'T_USE') {
                $pointer = $foundPosition + 2;
                $endOfNew = $file->findNext([T_SEMICOLON, T_OPEN_PARENTHESIS, T_WHITESPACE, T_DOUBLE_COLON], $pointer);
                $classNameParts = array_slice($tokens, $pointer, $endOfNew - $foundPosition - 2);
                $classNames[] = $this->buildClassName($classNameParts);
            }

            if ($currentToken['type'] === 'T_DOUBLE_COLON') {
                $pointer = $foundPosition + 1;
                $startOf = $file->findPrevious([T_OPEN_PARENTHESIS, T_WHITESPACE, T_OPEN_SQUARE_BRACKET], $foundPosition - 1) + 1;
                $classNameParts = array_slice($tokens, $startOf, $foundPosition - $startOf);
                $classNames[] = $this->buildClassName($classNameParts);
            }
        }

        $classNames = array_unique($classNames);

        foreach ($classNames as $className) {
            $className = ltrim($className, '\\');

            if (strpos($className, '_') === false && strpos($className, '\\') === false) {
                continue;
            }

            if (strpos($className, 'Spryker') !== false
                || strpos($className, 'Generated') !== false
                || strpos($className, 'Orm') !== false
                || strpos($className, 'static') !== false
                || strpos($className, 'self') !== false
            ) {
                continue;
            }

            $dependencyInformation[DependencyTree::META_FOREIGN_LAYER] = 'external';
            $dependencyInformation[DependencyTree::META_FOREIGN_CLASS_NAME] = $className;
            $dependencyInformation[DependencyTree::META_FOREIGN_IS_EXTERNAL] = true;

            $this->addDependency($fileInfo, 'external', $dependencyInformation);
        }
    }

    /**
     * @param array $classNameParts
     *
     * @return string
     */
    private function buildClassName(array $classNameParts)
    {
        $className = '';
        foreach ($classNameParts as $classNamePart) {
            $className .= $classNamePart['content'];
        }

        return $className;
    }

}
